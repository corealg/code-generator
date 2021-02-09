#!/bin/bash

WORKSPACE="${WORKSPACE:-`pwd`}"
is_jenkins=0
keep_sqlite=0
test_filters=""
sqlite_container_name="sqlite-test-${BUILD_NUMBER:-local}"
phpunit_container_name="api-phpunit-test-${BUILD_NUMBER:-local}"
# sqlite_container_opts="-p 3307:3306"

# while getopts "e:f:k" opt; do
#     case $opt in
#         e)
#             if [ "$OPTARG" == "jenkins" ]; then
#                 is_jenkins=1
#                 # Jenkins uses container networking, so we don't have to expose a port
#                 sqlite_container_opts=""
#             fi
#             break;
#             ;;
#         f)
#             if [ "$OPTARG" != "" ]; then
#                 test_filters="--filter $OPTARG"
#             fi
#             break;
#             ;;
#         k)
#             keep_sqlite=1
#             break;
#             ;;
#         \?)
#             echo "Invalid option: -$OPTARG" >&2
#             ;;
#     esac
# done

echo "Starting Test DB:  ${sqlite_container_name}"
IGNORE=`docker stop ${sqlite_container_name} 2> /dev/null`
IGNORE=`docker rm ${sqlite_container_name} 2> /dev/null`
IGNORE=`docker run --name ${sqlite_container_name} ${sqlite_container_opts} nouchka/sqlite3`
# sleep 10

# if [[ "${is_jenkins}" == "0" ]]; then
#     phpunit_container_name="dashboard-php7.3"
#     docker exec -it -w /portal -e TESTING_DB_HOST=host.docker.internal -e TESTING_DB_PORT=3307 ${phpunit_container_name} vendor/bin/phpunit ${test_filters}
#     TEST_STATUS=$?
# else
#     docker build -f docker/Dockerfile-php7.3 -t ubuntu-php7.3 ./docker
#     docker run --name ${phpunit_container_name} -t -u 106:111 -v ${WORKSPACE}:/app -w /app -e TESTING_DB_HOST=127.0.0.1 -e TESTING_DB_PORT=3306 --network container:${sqlite_container_name} ubuntu-php7.3 vendor/bin/phpunit ${test_filters}
#     TEST_STATUS=`docker inspect ${phpunit_container_name} --format='{{.State.ExitCode}}'`
# fi

# if [[ "${is_jenkins}" != "0" ]]; then
#     #Only remove PHP Container if we're in jenkins
#     IGNORE=`docker stop ${phpunit_container_name} && docker rm ${phpunit_container_name}`
# fi

# if [[ "${keep_sqlite}" == "0" ]]; then
#     IGNORE=`docker stop ${sqlite_container_name} && docker rm ${sqlite_container_name}`
# fi

# if [[ "$TEST_STATUS" != "0" ]]; then
#     echo "TESTS FAILURE"
#     exit 1
# else
#     echo "TESTS SUCCESS"
# fi
