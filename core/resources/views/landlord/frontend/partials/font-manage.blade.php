@php
      $scanned_directory = [];
       $body_fonts = [];

        if(is_dir('assets/landlord/frontend/custom-fonts')){
            $scanned_directory =  array_diff(scandir('assets/landlord/frontend/custom-fonts'), array('..', '.'));
        }

        foreach ($scanned_directory as $item)
            {
                $body_fonts[] = $item;
            }
@endphp



@if(!empty(get_static_option('custom_font')) )

    <style>

        @foreach($body_fonts as $bFont)
           @php
                $explode = explode('.',$bFont);
                $path = 'assets/landlord/frontend/custom-fonts/';

                $heading_font_selected = get_static_option('custom_heading_font');
                $body_font_selected = get_static_option('custom_body_font');

                $heading_font_full_path = $path . $heading_font_selected. '.'. end($explode);
                $body_font_full_path = $path .$body_font_selected. '.'. end($explode);

           @endphp
/*heading font*/
        @font-face {
            font-family: {{$heading_font_selected}};
            src: url('{{$heading_font_full_path}}') format('woff');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        /*body font*/
        @font-face {
            font-family: {{$body_font_selected}};
            src: url('{{$body_font_full_path}}') format('woff');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --heading-font: '{{$heading_font_selected}}', sans-serif !important;
            --body-font: '{{$body_font_selected}}', sans-serif !important;
        }
        @endforeach
    </style>
@else
    {!! load_google_fonts_landlord() !!}
@endif
