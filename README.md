clona o repositorio

deleta o arquivo storage na pasta /public

abre o diretorio do projeto no terminal e rode: composer install

php artisan storage:link

php artisan migrate:fresh --seed

esses comandos sao suficientes pro projeto rodar.


sobre o chatbot, como o php do senai é velho, vamos precisar de [CA Cert](https://curl.se/docs/caextract.html)

baixa, renomeia para cacert.pem

mova para pasta laragon/etc/ssl/

proximo passo, abra a pasta laragon/bin/php/php-8.1.10-Win32-vs16-x64 e procure pelo arquivo php.ini e abra com um editor de texto.

localize a frase "curl.cainfo" e mude para o caminho exato do cacert. exemplo: curl.cainfo = "E:/Users/xsm/Documents/laragon/etc/ssl/cacert.pem"

e um pouco abaixo dele vai ter o "openssl.cafile" coloque o mesmo caminho que vc botou no curl.cainfo. exemplo: openssl.cafile = "E:/Users/xsm/Documents/laragon/etc/ssl/cacert.pem"

e pronto.
