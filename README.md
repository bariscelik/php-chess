## Giriş

Çevrimiçi satranç oyunu projesi [Phalcon](https://phalconphp.com) ve [jQuery](https://jquery.com) kütüphaneleri kullanılarak oluşturulmuş, deneysel bir eğitim projesidir. Oyunun mümkün oldukça basit seviyede olması amaçlanmıştır.

Satranç oyununun kuralları baz alınarak, karşılıklı çevrimiçi oyuna imkan tanıyan bir yazılım oluşturulmuştur. 

Bu projeye katkı sunmak için [bu repoyu](https://github.com/bariscelik/php-chess) kullanabilirsiniz.

## Bölümler

### Üyeler

Oyuna dahil olmak isteyen kullanıcılar bir hesap oluşturup, masa açma hakkına sahip olabilirler. 

Üyelik sistemi:
- kullanıcı kaydı
- aktivasyonu
- girişi
- şifremi unuttum özelliği
- kullanıcı puanı

ile sınırlı tutulmuştur.

### Masalar

Oyunda birden fazla masa açılabilir. Yeni masa açan kullanıcı, bir rakibine davetiye gönderebilir ya da masaya birinin dahil olmasını bekleyebilir.

Masaya dahil olma talebinde bulunan kullanıcı, masa sahibi tarafından onaylandığı takdirde oyun başlar.
