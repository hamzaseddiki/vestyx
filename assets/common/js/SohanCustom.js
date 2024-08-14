class SohanCustom{
    static load_topbar_cart_nad_wishlist()
    {
        $('#track-icon-list').load(location.href + " #track-icon-list");
        $('#track-icon-wishlist').load(location.href + " #track-icon-wishlist");
        $('.track-icon-list').load(location.href + " .track-icon-list");
        $('#compare_li').load(location.href + " #compare_li");
    }
}

