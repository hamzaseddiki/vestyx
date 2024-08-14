@php
$value = isset($value) ?: '';

@endphp
<div class="form-group">
    <label for="name">{{__('Language')}}</label>
    <input type="hidden" name="name">
    <select name="language_select" class="form-control">
        <option @if($value === "af") @endif value="af" lang="af">Afrikaans</option>
        <option @if($value === "ar") @endif value="ar" lang="ar">العربية</option>
        <option @if($value === "ary") @endif value="ary" lang="ar">العربية المغربية</option>
        <option @if($value === "as") @endif value="as" lang="as">অসমীয়া</option>
        <option @if($value === "az") @endif value="az" lang="az">Azərbaycan dili</option>
        <option @if($value === "azb") @endif value="azb" lang="az">گؤنئی آذربایجان</option>
        <option @if($value === "bel") @endif value="bel" lang="be">Беларуская мова</option>
        <option @if($value === "bg_BG") @endif value="bg_BG" lang="bg">Български</option>
        <option @if($value === "bn_BD") @endif value="bn_BD" lang="bn">বাংলা</option>
        <option @if($value === "bo") @endif value="bo" lang="bo">བོད་ཡིག</option>
        <option @if($value === "bs_BA") @endif value="bs_BA" lang="bs">Bosanski</option>
        <option @if($value === "ca") @endif value="ca" lang="ca">Català</option>
        <option @if($value === "ceb") @endif value="ceb" lang="ceb">Cebuano</option>
        <option @if($value === "cs_CZ") @endif value="cs_CZ" lang="cs">Čeština</option>
        <option @if($value === "cy") @endif value="cy" lang="cy">Cymraeg</option>
        <option @if($value === "da_DK") @endif value="da_DK" lang="da">Dansk</option>
        <option @if($value === "de_CH") @endif value="de_CH" lang="de">Deutsch (Schweiz)</option>
        <option @if($value === "de_AT") @endif value="de_AT" lang="de">Deutsch (Österreich)</option>
        <option @if($value === "de_CH_informal") @endif value="de_CH_informal" lang="de">Deutsch (Schweiz, Du)</option>
        <option @if($value === "de_DE") @endif value="de_DE" lang="de">Deutsch</option>
        <option @if($value === "de_DE_formal") @endif value="de_DE_formal" lang="de">Deutsch (Sie)</option>
        <option @if($value === "dsb") @endif value="dsb" lang="dsb">Dolnoserbšćina</option>
        <option @if($value === "dzo") @endif value="dzo" lang="dz">རྫོང་ཁ</option>
        <option @if($value === "el") @endif value="el" lang="el">Ελληνικά</option>
        <option @if($value === "en_US") @endif value="en_US" lang="en">English (USA)</option>
        <option @if($value === "en_AU") @endif value="en_AU" lang="en">English (Australia)</option>
        <option @if($value === "en_GB") @endif value="en_GB" lang="en">English (UK)</option>
        <option @if($value === "en_CA") @endif value="en_CA" lang="en">English (Canada)</option>
        <option @if($value === "en_ZA") @endif value="en_ZA" lang="en">English (South Africa)</option>
        <option @if($value === "en_NZ") @endif value="en_NZ" lang="en">English (New Zealand)</option>
        <option @if($value === "eo") @endif value="eo" lang="eo">Esperanto</option>
        <option @if($value === "es_AR") @endif value="es_AR" lang="es">Español de Argentina</option>
        <option @if($value === "es_EC") @endif value="es_EC" lang="es">Español de Ecuador</option>
        <option @if($value === "es_MX") @endif value="es_MX" lang="es">Español de México</option>
        <option @if($value === "es_ES") @endif value="es_ES" lang="es">Español</option>
        <option @if($value === "es_VE") @endif value="es_VE" lang="es">Español de Venezuela</option>
        <option @if($value === "es_CO") @endif value="es_CO" lang="es">Español de Colombia</option>
        <option @if($value === "es_CR") @endif value="es_CR" lang="es">Español de Costa Rica</option>
        <option @if($value === "es_PE") @endif value="es_PE" lang="es">Español de Perú</option>
        <option @if($value === "es_PR") @endif value="es_PR" lang="es">Español de Puerto Rico</option>
        <option @if($value === "es_UY") @endif value="es_UY" lang="es">Español de Uruguay</option>
        <option @if($value === "es_GT") @endif value="es_GT" lang="es">Español de Guatemala</option>
        <option @if($value === "es_CL") @endif value="es_CL" lang="es">Español de Chile</option>
        <option @if($value === "et") @endif value="et" lang="et">Eesti</option>
        <option @if($value === "eu") @endif value="eu" lang="eu">Euskara</option>
        <option @if($value === "fa_IR") @endif value="fa_IR" lang="fa">فارسی</option>
        <option @if($value === "fa_AF") @endif value="fa_AF" lang="fa">(فارسی (افغانستان</option>
        <option @if($value === "fi") @endif value="fi" lang="fi">Suomi</option>
        <option @if($value === "fr_FR") @endif value="fr_FR" lang="fr">Français</option>
        <option @if($value === "fr_BE") @endif value="fr_BE" lang="fr">Français de Belgique</option>
        <option @if($value === "fr_CA") @endif value="fr_CA" lang="fr">Français du Canada</option>
        <option @if($value === "fur") @endif value="fur" lang="fur">Friulian</option>
        <option @if($value === "gd") @endif value="gd" lang="gd">Gàidhlig</option>
        <option @if($value === "gl_ES") @endif value="gl_ES" lang="gl">Galego</option>
        <option @if($value === "gu") @endif value="gu" lang="gu">ગુજરાતી</option>
        <option @if($value === "haz") @endif value="haz" lang="haz">هزاره گی</option>
        <option @if($value === "he_IL") @endif value="he_IL" lang="he">עִבְרִית</option>
        <option @if($value === "hi_IN") @endif value="hi_IN" lang="hi">हिन्दी</option>
        <option @if($value === "hr") @endif value="hr" lang="hr">Hrvatski</option>
        <option @if($value === "hsb") @endif value="hsb" lang="hsb">Hornjoserbšćina</option>
        <option @if($value === "hu_HU") @endif value="hu_HU" lang="hu">Magyar</option>
        <option @if($value === "hy") @endif value="hy" lang="hy">Հայերեն</option>
        <option @if($value === "id_ID") @endif value="id_ID" lang="id">Bahasa Indonesia</option>
        <option @if($value === "is_IS") @endif value="is_IS" lang="is">Íslenska</option>
        <option @if($value === "it_IT") @endif value="it_IT" lang="it">Italiano</option>
        <option @if($value === "ja") @endif value="ja" lang="ja">日本語</option>
        <option @if($value === "jv_ID") @endif value="jv_ID" lang="jv">Basa Jawa</option>
        <option @if($value === "ka_GE") @endif value="ka_GE" lang="ka">ქართული</option>
        <option @if($value === "kab") @endif value="kab" lang="kab">Taqbaylit</option>
        <option @if($value === "kk") @endif value="kk" lang="kk">Қазақ тілі</option>
        <option @if($value === "km") @endif value="km" lang="km">ភាសាខ្មែរ</option>
        <option @if($value === "kn") @endif value="kn" lang="kn">ಕನ್ನಡ</option>
        <option @if($value === "ko_KR") @endif value="ko_KR" lang="ko">한국어</option>
        <option @if($value === "ckb") @endif value="ckb" lang="ku">كوردی‎</option>
        <option @if($value === "lo") @endif value="lo" lang="lo">ພາສາລາວ</option>
        <option @if($value === "lt_LT") @endif value="lt_LT" lang="lt">Lietuvių kalba</option>
        <option @if($value === "lv") @endif value="lv" lang="lv">Latviešu valoda</option>
        <option @if($value === "mk_MK") @endif value="mk_MK" lang="mk">Македонски јазик</option>
        <option @if($value === "ml_IN") @endif value="ml_IN" lang="ml">മലയാളം</option>
        <option @if($value === "mn") @endif value="mn" lang="mn">Монгол</option>
        <option @if($value === "mr") @endif value="mr" lang="mr">मराठी</option>
        <option @if($value === "ms_MY") @endif value="ms_MY" lang="ms">Bahasa Melayu</option>
        <option @if($value === "my_MM") @endif value="my_MM" lang="my">ဗမာစာ</option>
        <option @if($value === "nb_NO") @endif value="nb_NO" lang="nb">Norsk bokmål</option>
        <option @if($value === "ne_NP") @endif value="ne_NP" lang="ne">नेपाली</option>
        <option @if($value === "nl_NL") @endif value="nl_NL" lang="nl">Nederlands</option>
        <option @if($value === "nl_BE") @endif value="nl_BE" lang="nl">Nederlands (België)</option>
        <option @if($value === "nl_NL_formal") @endif value="nl_NL_formal" lang="nl">Nederlands (Formeel)</option>
        <option @if($value === "nn_NO") @endif value="nn_NO" lang="nn">Norsk nynorsk</option>
        <option @if($value === "oci") @endif value="oci" lang="oc">Occitan</option>
        <option @if($value === "pa_IN") @endif value="pa_IN" lang="pa">ਪੰਜਾਬੀ</option>
        <option @if($value === "pl_PL") @endif value="pl_PL" lang="pl">Polski</option>
        <option @if($value === "ps") @endif value="ps" lang="ps">پښتو</option>
        <option @if($value === "pt_BR") @endif value="pt_BR" lang="pt">Português do Brasil</option>
        <option @if($value === "pt_PT_ao90") @endif value="pt_PT_ao90" lang="pt">Português (AO90)</option>
        <option @if($value === "pt_AO") @endif value="pt_AO" lang="pt">Português de Angola</option>
        <option @if($value === "pt_PT") @endif value="pt_PT" lang="pt">Português</option>
        <option @if($value === "rhg") @endif value="rhg" lang="rhg">Ruáinga</option>
        <option @if($value === "ro_RO") @endif value="ro_RO" lang="ro">Română</option>
        <option @if($value === "ru_RU") @endif value="ru_RU" lang="ru">Русский</option>
        <option @if($value === "sah") @endif value="sah" lang="sah">Сахалыы</option>
        <option @if($value === "snd") @endif value="snd" lang="sd">سنڌي</option>
        <option @if($value === "si_LK") @endif value="si_LK" lang="si">සිංහල</option>
        <option @if($value === "sk_SK") @endif value="sk_SK" lang="sk">Slovenčina</option>
        <option @if($value === "skr") @endif value="skr" lang="skr">سرائیکی</option>
        <option @if($value === "sl_SI") @endif value="sl_SI" lang="sl">Slovenščina</option>
        <option @if($value === "sq") @endif value="sq" lang="sq">Shqip</option>
        <option @if($value === "sr_RS") @endif value="sr_RS" lang="sr">Српски језик</option>
        <option @if($value === "sv_SE") @endif value="sv_SE" lang="sv">Svenska</option>
        <option @if($value === "sw") @endif value="sw" lang="sw">Kiswahili</option>
        <option @if($value === "szl") @endif value="szl" lang="szl">Ślōnskŏ gŏdka</option>
        <option @if($value === "ta_IN") @endif value="ta_IN" lang="ta">தமிழ்</option>
        <option @if($value === "ta_LK") @endif value="ta_LK" lang="ta">தமிழ்</option>
        <option @if($value === "te") @endif value="te" lang="te">తెలుగు</option>
        <option @if($value === "th") @endif value="th" lang="th">ไทย</option>
        <option @if($value === "tl") @endif value="tl" lang="tl">Tagalog</option>
        <option @if($value === "tr_TR") @endif value="tr_TR" lang="tr">Türkçe</option>
        <option @if($value === "tt_RU") @endif value="tt_RU" lang="tt">Татар теле</option>
        <option @if($value === "tah") @endif value="tah" lang="ty">Reo Tahiti</option>
        <option @if($value === "ug_CN") @endif value="ug_CN" lang="ug">ئۇيغۇرچە</option>
        <option @if($value === "uk") @endif value="uk" lang="uk">Українська</option>
        <option @if($value === "ur") @endif value="ur" lang="ur">اردو</option>
        <option @if($value === "uz_UZ") @endif value="uz_UZ" lang="uz">O‘zbekcha</option>
        <option @if($value === "vi") @endif value="vi" lang="vi">Tiếng Việt</option>
        <option @if($value === "zh_TW") @endif value="zh_TW" lang="zh">繁體中文</option>
        <option @if($value === "zh_HK") @endif value="zh_HK" lang="zh">香港中文版	</option>
        <option @if($value === "zh_CN") @endif value="zh_CN" lang="zh">简体中文</option>
    </select>
</div>
