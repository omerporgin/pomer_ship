{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="https://www.google.com/schemas/sitemap-image/1.1">
    {{-- ALWAYS --}}
    <url>
        <loc>{{ url('') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('blogs') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1</priority>
    </url>
    {{-- MONTHLY --}}
    <url>
        <loc>{{ url('contact') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('price') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('faq') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('partners') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('feature') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('login') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ url('register') }}</loc>
        <lastmod>{{ date("Y-m-d") }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1</priority>
    </url>

    @foreach($blogList as $item)
        <url>
            <loc>{{ url($item->link) }}</loc>
            <lastmod>{{ $item->updatedAt }}</lastmod>
            <changefreq>{{ $item->changefreq }}</changefreq>
            <priority>{{ $item->priority }}</priority>
            @if(isset($item->images))
                @foreach($item->images as  $image)
                    <image:image>
                        <image:loc>{{ url($image) }}</image:loc>
                    </image:image>
                @endforeach
            @endif
        </url>
    @endforeach
</urlset>
