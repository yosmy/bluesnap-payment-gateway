# Test

docker network create backend

cd test

export UID
export GID
docker-compose \
-f docker/all.yml \
-p yosmy_bluesnap_gateway \
up -d \
--remove-orphans --force-recreate

docker exec -it yosmy_bluesnap_gateway_php sh
cd test
rm -rf var/cache/*

php bin/app.php /payment/gateway/bluesnap/add-customer
php bin/app.php /payment/gateway/bluesnap/add-card 26490261 5425233430109903 04 23 123
php bin/app.php /payment/gateway/bluesnap/add-card 26490261 2222420000001113 08 20 456
php bin/app.php /payment/gateway/bluesnap/add-card 26490261 2223000048410010 09 20 789
php bin/app.php /payment/gateway/bluesnap/delete-card 26490261 5e2f0e19ded09
php bin/app.php /payment/gateway/bluesnap/execute-charge 26490261 5e2f0e668e08e 1000 "Deposito" "Deposito"
php bin/app.php /payment/gateway/bluesnap/refund-charge 1028421865

Invalid exp. date
php bin/app.php /payment/gateway/bluesnap/add-card 26490261 5425233430109903 12 04 123