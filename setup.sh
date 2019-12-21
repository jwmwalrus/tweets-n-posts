#!/bin/bash

all () {
    BUILD_PROD=1
    DROP_DATABASE=1
    SEED_DATABASE=1
    build

}

build () {
    ci

    if [[ ! -z ${BUILD_PROD} ]]; then
        runprod
    else
        rundev
    fi

    initdb
}

ci () {
    rm -rf vendor
    composer install
    rm -rf node_modules

    if [[ -f package-lock.json ]]; then
        npm ci
    else
        npm install
    fi
}

createdb () {
    bin/console doctrine:database:drop --if-exists --force -n

    bin/console doctrine:database:create --if-not-exists -n
}

genrsa () {
    if [[ -f config/jwt/private.pem ]]; then
        rm config/jwt/private.pem
    fi

    if [[ -f config/jwt/public.pem ]]; then
        rm config/jwt/public.pem
    fi

    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096

    exit_code=$?
    if [[ "${exit_code}" == "0" ]]; then
        openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    fi
}

initdb () {
    if [[ ! -z ${DROP_DATABASE} ]]; then
        createdb
    fi

    bin/console doctrine:migrations:migrate -n

    if [[ ! -z ${SEED_DATABASE} ]]; then
        bin/console doctrine:fixtures:load -n
    fi
}

rundev () {
    rm -rf public/dist
    npm run dev
}

runprod () {
    rm -rf public/dist
    npm run prod
}

showhelp () {
    echo -e "\nRun setup commands\n"
    echo -e "USAGE: ./setup.sh COMMAND [OPTIONS]\n"
    echo -e "COMMANDS:\n"
    echo "    all             Run \`./setup.sh build -d --seed --prod\`"
    echo "    build           Run the \`ci\`, \`rundev\` and \`initdb\` commands"
    echo "    ci              Install the project's dependencies"
    echo "    createdb        Creates the database"
    echo "    genrsa          Generates RSA keys for JWT purposes"
    echo "    initdb          Adds the database schema and, optionally, seeds the tables"
    echo "    rundev          Generates the public/dist directory in development mode"
    echo "    runprod         Generates the public/dist directory in production mode"
    echo -e "\nOPTIONS:\n"
    echo "    -d                  Drop the existing database before initializing"
    echo "    -h,--help           Show this message and exit"
    echo "    --prod              Build in production mode"
    echo "    -s,--seed           Seed the database after initializing"

    exit 0
}


COMMAND=
BUILD_PROD=
DROP_DATABASE=
SEED_DATABASE=

for arg in "$@"
do
    case $arg in
        all | build | ci | createdb | genrsa | initdb | rundev | runprod)
            COMMAND=${arg}
            ;;
        -d)
            DROP_DATABASE=1
            ;;
        -h | --help)
            showhelp
            ;;
        --prod)
            BUILD_PROD=1
            ;;
        -s | --seed)
            SEED_DATABASE=1
            ;;
        *)
            showhelp
            ;;
    esac
done

if [[ ! -z ${COMMAND} ]]; then
    ${COMMAND}
fi

exit 0
