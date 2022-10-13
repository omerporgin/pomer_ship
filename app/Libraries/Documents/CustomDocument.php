<?php

namespace App\Libraries\Documents;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;

use App\Services\OrderService;


class CustomDocument extends AbstractDocument
{

    /**
     * @param int $orderId
     */
    public function __construct(OrderService $order)
    {
        $this->order = $order;
        $this->create();
    }

    /**
     * @param int $orderId
     * @return void
     */

    public function create()
    {

        $outputFile = Storage::disk('local')->path($this->saveAs('custom'));

        $pdf = new Fpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->editFile($pdf);
        $pdf->Output($outputFile, 'F');

    }

    /**
     * @param $pdf
     * @param $totalPages
     * @param $path
     * @return void
     */
    public function editFile($pdf)
    {
        $package = $this->order->get()->orderPackages()->get();

        $user = $this->order->get()->user()->first();

        $productList = $this->order->get()->orderProducts()->get();

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $pdf->AddPage();

        $productContent = [];
        $gtipContent = [];
        foreach($productList as $product){
            $productContent[] = $product->name;
            $gtipContent[] = $product->gtip_code;
        }
        // Table with rowspans and THEAD
        $pdf->Rect(0, 0, 400, 20, 'F', [], array(254,204,0) );
        $image_file = public_path('/DHL-Logo.png');
        $pdf->Image($image_file, 155, 5, 45, 10, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false,
            false);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('dejavusans', '', 7.5);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setXY(0,12);

        $html = '
            <h2>MİKRO İHRACAT EVRAK SETİ</h2>
            <br>
            <div style="text-align: center">
                <h2>GÜMRÜK DOLAYLI TEMSİL YETKİSİ</h2>
            </div>
            I. TARAFLAR
            <ol>
            <li>
            Tayakadın Mah., Nuri Demirağ Cad Kargo Bölgesi, No: 9 Arnavutköy 34283 İSTANBUL adresinde faaliyet gösteren <b>DHL Worldwide
            Express Taşımacılık ve Ticaret A.Ş.</b> (Kısaca Taşıyıcı Firma ve/veya “DHL” olarak anılacaktır)
            </li>
            <li> [Adres:]<u>'.$user->warehouse_address.' '.$user->warehouse_postal_code.' '.$user->city_name.'/'
            .$user->state_name.'</u> (posta kodu dahil) adresinde faaliyet gösteren [Vergi
            Kimlik No:] <u>'.$user->real_tax_id.'</u> vergi numaralı [Unvan:]<u>'.$user->real_owner_name.'</u> (Kısaca “Müşteri” olarak anılacaktır) firması
            arasında imzalanmıştır. (Eğer şahıs ise İsim Soy isim ve TC Kimlik No olarak doldurulması gerekir)
            Müşterinin İmza Yetkili Personeli Adı, Soyadı: <u>'.$user->full_name.'</u> ve telefon no: <u>'
            .$user->warehouse_phone.'</u>
            Müşterinin DHL Aboneliği var ise Abone numarası: .............................................
            </li>
            </ol>
            II. KONU
            <ol>
            <li>İşbu <b>yetkilendirme</b> ile, DHL, 28.01.2010 tarihli 27476 numaralı resmi gazetede yayımlanan, Gümrük Genel Tebliği, Posta ve Hızlı
            Kargo Taşımacılığı, Seri No: 1 gereği, aşağıda belirtilen şartlarda MÜŞTERİ’ yi, gönderisinin İstanbul Havalimanı Kargo Gümrük
            Müdürlüğü’ nde işlemleri ile ilgili olarak dolaylı temsil edecektir.
            <br>
            <table>
                <tr>
                <td width="30"></td>
                <td width="430"> Yetkilendirme genel olarak verilecek ise, bu kutucuğu işaretleyiniz. <b>Genel talimattır</b>.<br>
                Yetkilendirme tek konşimento bazında yetki verilecek ise, bu kutucuğu işaretleyiniz. <b>Özel talimattır</b>.<br>
                ve ayrıca aşağıya konşimento numarasını ve istenilen bilgileri yazınız.<br>
                Konşimento no: <u>'.$package[0]?->tracking_number.'</u>
                İçerik: <u><i>'.implode(',', $productContent)  .'</i></u><br>
                GTİP (Gümrük Tarife İstatistik Pozisyon Numarası):  <u><i>'.implode(',', $gtipContent)  .'</i></u><br>
                </td>
                </tr>
            </table>
            </li>
            <li>İşbu yetkilendirme ile, DHL, eşyanın Gümrük Prosedürleri ile ilgili olarak Müşteri tarafından yetkili vekil tayin edilmiştir. Yukarıda
            belirtilen Tebliğ kapsamına istinaden, DHL, gönderinin gümrük çekim işlemlerine ait tüm eylemlerde Müşteri adına ve namına işlem
            yapmaya yetkili kılınmıştır. Gönderinin dağıtım adresi Müşteri’ nin konşimentoda belirtilen adresidir. Müşteri gümrükte oluşan tüm
            gümrük masraflarını (vergiler, ardiye, gümrük çekim evrakları çekim ücretleri, terminal hizmetleri ücretleri ve diğer tüm ücret ve
            masrafları) DHL’e ödemekle yükümlüdür. Ek olarak, MÜŞTERİ gümrükte çekim sürecinde oluşan tüm masrafları da ödemeyi beyan,
            kabul, taahhüt ve garanti etmektedir.
            </li>
            <li>
            Tüm ödemeler nakit veya banka havalesi ile DHL’in Banka hesabına yapılacaktır: Banka Bilgisi: Hesap Sahibi: DHL Worldwide
            Express Taşımacılık ve Ticaret Anonim Şirketi, GARANTİ Bankası, Bakırköy Kurumsal Şubesi, Şube Kodu: 382, Hesap No: 6297122.
            IBAN NO: TR59 0006 2000 3820 0006 2971 22.
            </li>
            </ol>
            III. YÜKÜMLÜLÜKLER ve DİĞER HÜKÜMLER:
            <br>';

            $html2 = '<div> Tebliğ’in öngördüğü kuralların yerine getirilmesine istinaden, Müşteri adına dolaylı temsilci olarak ithalat ve ihracat işlemi
            yapabilmesi için, Müşteri DHL’e yetki vermiştir. Bahsi geçen gümrük işlemlerinin yapılması sırasında, yetkili kılınan DHL’ in
            gümrükteki tüm beyanname ve ödeme formları ile çözüm getirme amaçlı anlaşma metinlerine, feragat etmek amaçlı anlaşma
            metinlerinde karar alma ve imza yetkisi de Müşteri tarafından DHL’e verilmiştir. Ek olarak, Müşteri adına yapılan gümrüksel
            işlemlerdeki tüm ödemeleri kabul etme ve onaylama ile, gümrüksel evrakları ve evrakların kabul edilmesi aşamasındaki tüm
            anlaşmaları kabul etmek yetkisini Müşteri DHL’e vermiştir. Ayrıca, gümrüksel işlem süresince gereken tüm prosedürler gereğince
            DHL’e dilerse Müşteri adına, kendi ismiyle işlem yapma yetkisi de Müşteri tarafından verilmiştir. Müşteri, 2009/15481 sayılı
            Bakanlar Kurulu kararının 126. maddesindeki hızlı kargo taşımacılığı kapsamında gelen gönderinin, değeri 1.500 Avro ve ağırlığı brüt
            30 kg.’yi geçmeyen, giden gönderinin ise değeri 15000 Avro ve ağırlığı 300 kg.’yi geçmeyen eşya olduğunu, Serbest dolaşıma giriş
            rejimine konu ise, ek olarak; ticari miktar ve mahiyet arz etmeyen eşya olduğunu beyan, kabul, taahhüt, teyid ve garanti etmiştir.
            İşbu Sözleşme ile Müşteri aşağıda belirtilen sorumluluklar için DHL’i ve DHL personelini vekil olarak yetkili kılmıştır. Aynı zamanda,
            daha önceden gümrük çekimi gerçekleştirilmiş olan ETGB (Elektronik Ticaret Giriş Beyanı)’ lerin düzeltilmesi ve iyileştirilmesi
            aşamasında Müşteri tarafından DHL’e yetki verilmiştir. Müşteri’nin gümrük sistemine kaydının gerekmesi durumunda, Müşteri’nin
            evrakları hazırlaması zorunludur. Yetki veren, yetkilendirmenin aktif olarak başlamasını, adı geçen dokümanın DHL Gümrük Ofisine
            varışı ile başlatır ve aktif olarak tamamlanmasını DHL Gümrük Ofisi’ ne iptal bildirisinin gelmesi ile olduğunu kabul eder. DHL, yetkili
            kişi imzasının, imza sirkülerindeki yetkilendirmeye uygun olduğunu kabul eder. Bununla alakalı olarak, gümrüksel evrakların yetkili
            kişi tarafından imzalanması gerekir. DHL’ in hazırlamış olduğu gümrük beyanının onaylanması, beyanı yapan firmanın içeriğini
            doğruluğunu kabul ettiğini gösterir. Gümrük beyanını imzalayan kişi, gümrüğe beyandan gümrükten çıkışa kadar olan süre boyunca,
            tüm işlemlerden sorumludur. DHL, Gümrük Otoritelerinin isteği doğrultusunda Yetkilendirmeyi ibraz etmeyi üstlenmektedir. DHL,
            yetki kapsamındaki gönderi çekimi sonrasında Müşteriye bütün dokümanları ibraz etmeyi üstlenmektedir. İşbu Sözleşme ile
            Müşteri, gümrük çekimi sırasında ve gönderinin ithalat, ihracat ve gümrüklü aktarımı ile ilgili ve yine Gümrük Otoritelerine sunmak
            maksadıyla DHL tarafından talep edilen tüm gerekli doküman ve bilgiyi temin etmeyi üstlenmektedir. Ek olarak, Müşteri ticaretteki
            yazılı ve diğer gümrük çekimi ile doğrudan veya potansiyel ilintili tüm dokümanlarda meydana gelen değişikliklerde DHL’ i
            bilgilendirmekle yükümlüdür. DHL, Müşteri’den kaynaklanan gecikme ve kayıptan sorumlu değildir; örneğin, gümrük prosedürü /
            gümrük çekimi veya eşya ile ilgili, gerekli evrakın temin edilememesi (Vergi Numarası, birlik kayıtları, gerekli bilgi ve belgeler vb.). Ek
            olarak, Müşteri bu Sözleşme ile gümrük prosedürü ve çekim işlemi tamamlanmış beyanların aralarındaki herhangi bir çelişki ve
            tutarsızlık durumunun DHL tarafından düzeltilmesi sürecini de üstlenmektedir. Gümrük otoritelerine şikayet dosyalama süreci
            beyan bilgilerinin kabulü ile başlayan 5 yıldır. Malzemelerin transit halinde olması halinde, Müşteri gönderiye ek olarak transit
            dokümanlarını gönderinin çekileceği ilgili gümrük ofisine zamanında ve eksiksiz olarak ibraz etmekle yükümlüdür. Gönderinin
            gümrük çekimi aşamasında meydana gelen hasarlarda DHL’ in sorumluluğu kısıtlıdır. İşbu Yetkilendirme süresiz şekilde yapılmıştır.
            Taraflar, yazılı uyarı ile Yetkilendirmeyi tek taraflı fesih hakkına sahiptir. Uyarı süresi 30 (otuz) gündür ve bu süre yazılı uyarının karşı
            tarafa ulaşması ile başlar. İşbu Yetkilendirme, Türkiye Cumhuriyeti sınırları dahilinde, Türkiye Cumhuriyeti Anayasasına uygun
            olarak hüküm sürmektedir. Uyuşmazlıkların çözümünde İstanbul Mahkemeleri ve İcra Daireleri yetkilidir.</div>
            <br>
           <table border="0">
                <tr>
                <td width="300">
                    <b>AD – SOYAD / KAŞE / İMZA</b>
                </td>
                <td width="150">
                    Form No : ........................................<br>
                    Revizyon No : ..................................<br>
                    Revizyon Tarihi No : ........................<br>
                    Arşiv Süresi No : ..............................<br>
                </td>
                </tr>
            </table>
            ';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->SetMargins(20, 10, 10, true);
        $pdf->writeHTML($html2, true, false, true, false, '');
    }

}

