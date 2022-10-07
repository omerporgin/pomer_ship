<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PagesController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function index($lang = 109)
    {

        $cacheName = 'index.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $blogList = [];
            foreach (\App\Models\Blog::limit(3)->get() as $blog) {
                $blogList[] = service('Blog', $blog->id);
            }
            $view = \View::make(theme('home'), [
                'lang' => $lang,
                'blogList' => $blogList,
            ]);
            return $view->render();
        });
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function contact($lang = 109)
    {
        $cacheName = 'contact.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $view = \View::make(theme('contact'), [
                'lang' => $lang
            ]);
            return $view->render();
        });
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function price($lang = 109)
    {
        $cacheName = 'price.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $view = \View::make(theme('price'), [
                'lang' => $lang
            ]);
            return $view->render();
        });
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function faq($lang = 109)
    {
        $cacheName = 'faq.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $list = [
                'ShipExporgin' => [
                    'ShipExporgin Nedir ? ' => 'ShipExporgin, Türkiye’den yurt dışına gönderi yapan / yapacak olan herkesin kullanabileceği bir kargo
                         ve yazılım hizmetidir . Kendinize özel ShipExporgin paneliniz üzerinden siparişlerinizi ve gönderilerinizi yönetirken,
                        ShipExporgin lojistik aracılığıyla söz konusu gönderilerinizin güvenle alıcılarınıza ulaşmasını sağlayabilirsiniz . ',
                    'ShipExporgin Nasıl Çalışır ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                    'ShipExporgin Üyeliği Ücretli Midir ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                ],
                'Hesap Ayarları' => [
                    'Kullanıcı Adımı Veya Bilgilerimi Nasıl Değiştirebilirim ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                    'Hesabımı Nasıl Kapatırım ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                ],
                'ShipExporgin Gönderi Merkezi' => [
                    'Kargolarımı ShipExporgin Merkezlerine Nasıl Ulaştırabilirm ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                    'Kargo Takip Numaralarını Nasıl Alabilirim ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum'
                ],
                'Gönderiler' => [
                    'Gönderim gümrüğe takılır mı,takılırsa ne yapabilirim ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                    'Ağır ve hacimli gönderilerde sınırlamalar var mı ? ' => '
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
                        ',
                    'Kargosu yapılmayacak yasaklı ürünler nelerdir ? ' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                         labore et dolore magna aliqua . Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                         consequat . Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur .
    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum'
                ],

            ];

            $view = \View::make(theme('faq'), [
                'lang' => $lang,
                'list' => $list
            ]);
            return $view->render();
        });
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function partners($lang = 109)
    {
        $cacheName = 'partners.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $view = \View::make(theme('partners'), [
                'lang' => $lang
            ]);
            return $view->render();
        });
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function feature($lang = 109)
    {

        $cacheName = 'feature.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $view = \View::make(theme('feature'), [
                'lang' => $lang
            ]);
            return $view->render();
        });
    }
}
