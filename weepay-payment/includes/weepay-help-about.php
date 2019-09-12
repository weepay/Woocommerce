<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$weepay_url = plugins_url() . '/weepay-payment/';
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>.continue-button {
    width: 280px;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: -.33px;
    border-radius: 31px;
    background-image: linear-gradient(279deg,#5170e9 ,#12bbe2);
    height: 40px;
    line-height: 38px;
    border-radius: 100px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: -.33px;
    text-align: center;
    cursor: pointer;
    border: none;
    outline: 0;
    padding: 0 20px;
}</style>
    <div class="panel">
        <div class="row weepay-header">
            <img src="<?php echo $weepay_url ?>img/logo.png" class="col-xs-4 col-md-2 text-center" id="payment-logo" />
            <div class="col-xs-6 col-md-5 text-center">
                <h4>weepay Ödeme Hizmetleri</h4>
                <h4>Hızlı Güvenli ve Kolay</h4>
            </div>
            <div class="col-xs-12 col-md-5 text-center">
                <a href="https://weepay.co" class="btn continue-button" id="create-account-btn">weePos'a başvurun</a><br />
                weePos'unuz varsa ?<a href="https://pos.weepay.co"> Hesabınıza giriş yapın</a>
            </div>
        </div>

        <hr />


        <div class="weepay-content">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="thumbnail">
                        <figure class="figure text-center">
                            <img src="<?php echo $weepay_url ?>img/ertesigun-icon.svg" width="140" height="100"/>
                        </figure>
                        <p class="text text-center">
                            7x24 kesintisiz
                            <br>Ertesi iş günü hesabınızda
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="thumbnail">
                        <figure class="figure text-center">
                            <img src="<?php echo $weepay_url ?>img/hesaplisatis.svg" width="140" height="100"/>
                        </figure>
                        <p class="text text-center">
                            Hesaplı
                            <br>satış avantajı
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="thumbnail">
                        <figure class="figure text-center">
                            <img src="<?php echo $weepay_url ?>img/butunkredikartlari.svg" width="140" height="100"/>
                        </figure>
                        <p class="text text-center">
                            Bütün kredi kartları için
                            <br>taksitli satış imkanı
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="thumbnail">
                        <figure class="figure text-center">
                            <img src="<?php echo $weepay_url ?>img/visamastercard.svg" width="140" height="100"/>
                        </figure>
                        <p class="text text-center">
                            Visa ve MasterCard
                            <br>tahsilat imkanı
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="thumbnail">
                        <figure class="figure text-center">
                            <img src="<?php echo $weepay_url ?>img/dolareuro.svg" width="140" height="100"/>
                        </figure>
                        <p class="text text-center">
                            Yabancı kartlar ile
                            <br>işlem yapabilme
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="thumbnail">
                        <figure class="figure text-center">
                            <img src="<?php echo $weepay_url ?>img/kolayentegrasyon.svg" width="140" height="100"/>
                        </figure>
                        <p class="text text-center">
                            Hızlı ve kolay
                            <br>entegrasyon
                        </p>
                    </div>
                </div>
            </div>
            <hr />
        </div>
    </div>

    