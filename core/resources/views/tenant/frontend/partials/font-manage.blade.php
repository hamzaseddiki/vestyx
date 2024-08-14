@php
    $scanned_directory = [];
    if(is_dir('assets/tenant/frontend/custom-fonts/'.tenant()->id)){
         $scanned_directory =  array_diff(scandir('assets/tenant/frontend/custom-fonts/'.tenant()->id), array('..', '.'));

         foreach ($scanned_directory as $item)
            {
                $body_fonts[] = $item;
            }
    }
@endphp


@if(!empty(get_static_option('custom_font')) )

    <style>
        @foreach($body_fonts as $bFont)
           @php
                $explode = explode('.',$bFont);
                $path = 'assets/tenant/frontend/custom-fonts/'.tenant()->id.'/';

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
    {!! load_google_fonts() !!}
@endif
