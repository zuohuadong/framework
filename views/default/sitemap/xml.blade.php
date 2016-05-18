{!! $top !!}
<urlset>
    @foreach($items as $item)
    <url>
        <loc>{{ $item['loc'] }}</loc>
        <lastmod>{{ date('Y-m-d\TH:i:sP', strtotime($item['lastmod'])) }}</lastmod>
        @if($item['freq'])
            <changefreq>{{ $item['freq'] }}</changefreq>
        @endif
        @if($item['priority'])
        <priority>{{ $item['priority'] }}</priority>
        @endif
        <data>
            <display>
                <title>{!! $item['title'] !!}</title>
            </display>
        </data>
    </url>
    @endforeach
</urlset>