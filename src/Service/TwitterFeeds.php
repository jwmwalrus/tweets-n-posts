<?php

namespace App\Service;

use App\Entity\Tweet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Collection;
use PHPHtmlParser\Selector\Parser;
use PHPHtmlParser\Selector\Selector;

/**
 * Processes Twitter feeds
 *
 */
class TwitterFeeds
{
    public const OEMBED_URL = 'https://publish.twitter.com/oembed';
    private const REQUEST_LIMIT = 50;
    /**
     * em
     *
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function update(): void
    {
        $users = $this->em->getRepository(User::class)->findAll();

        $limit = self::REQUEST_LIMIT;
        foreach ($users as $u) {
            $twitterId = $u->getTwitterid();
            // $oembed = $this->getOembed($twitterId);

            $dom = new Dom();
            try {
                $dom->loadFromUrl("https://twitter.com/{$twitterId}?ref_src=twsrc,tweet-limit=20,width=200");
            } catch (\Throwable $e) {
                continue;
            }
            $coll = $dom->find('.tweet.js-stream-tweet');
            $entries = $this->decompose($coll);
            $entries = $this->filterByUser($entries, $twitterId);
            $entries = $this->filterByItemId($entries);
            $this->saveToDatabase($entries, $u);
        }
    }

    /**
     * decompose
     *
     * @param Collection $collection
     *
     * @return array|mixed[]
     */
    private function decompose(Collection $collection): array
    {
        $result = [];

        foreach ($collection as $c) {
            $entry = [];

            $attr = $c->getTag()->getAttributes();
            $entry['item_id'] = $attr['data-item-id']['value'];
            $entry['screen_name'] = $attr['data-screen-name']['value'];
            $entry['user_id'] = $attr['data-user-id']['value'];

            $selector = new Selector('a.tweet-timestamp span._timestamp', new Parser());
            $stamp = $selector->find($c);
            $attr = $stamp->getTag()->getAttributes();
            $entry['time'] = intval($attr['data-time']['value']);

            $selector = new Selector('.js-tweet-text-container', new Parser());
            $twit = $selector->find($c);
            $entry['raw'] = $twit->outerHtml();
            $entry['plain'] = $twit->text(true);

            $result[] = $entry;
        }

        return $result;
    }

    /**
     * filterByUser
     *
     * @param array|mixed[] $entries
     * @param string $twitterId
     *
     * @return array|mixed[]
     */
    private function filterByUser(array $entries, string $twitterId): array
    {
        $newOnes = [];
        foreach ($entries as $e) {
            if (strtolower($e['screen_name']) === $twitterId) {
                $newOnes[] = $e;
            }
        }

        return $newOnes;
    }

    /**
     * filterByItemId
     *
     * @param array|mixed[] $entries
     *
     * @return array|mixed[]
     */
    private function filterByItemId(array $entries): array
    {
        $newOnes = [];

        foreach ($entries as $e) {
            $found = $this->em
                          ->getRepository(Tweet::class)
                          ->findBy(['itemid' => $e['item_id']]);
            if (empty($found)) {
                $newOnes[] = $e;
            }
        }

        return $newOnes;
    }

    private function getOembed(string $twitterId)
    {
        $headers = ['Accept' => 'application/json'];

        $params = [
            'url' => "https://twitter.com/{$twitterId}",
            ];

        $response = \Unirest\Request::get(self::OEMBED_URL, $headers, $params);

        return $response->body;
    }

    /**
     * saveToDatabase
     *
     * @param array|mixed[] $entries
     * @param User $owner
     */
    private function saveToDatabase(array $entries, User $owner): void
    {
        foreach ($entries as $e) {
            $tweet = new Tweet();
            $tweet->setItemid($e['item_id']);
            $tweet->setRaw($e['raw']);
            $tweet->setPlain($e['plain']);
            $tweet->setTimestamp($e['time']);
            $tweet->setOwner($owner);
            $this->em->persist($tweet);
        }
        $this->em->flush();
    }
}
