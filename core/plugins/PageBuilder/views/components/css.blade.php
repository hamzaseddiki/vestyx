<link rel="stylesheet" href="{{global_asset('assets/plugins/PageBuilder/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{global_asset('assets/plugins/PageBuilder/css/spectrum.min.css')}}">
<style>
    input::-webkit-calendar-picker-indicator{
        display: none;
    }
    input[type="date"]::-webkit-input-placeholder{
        visibility: hidden !important;
    }

    /* page builder style */



     .page-builder-area-wrapper .all-field-wrap {
        position: relative;
        background-color: #f2f2f2;
        padding: 30px;
        padding-right: 70px;
        margin-bottom: 30px;
    }

     .page-builder-area-wrapper .all-field-wrap .action-wrap {
        position: absolute;
        right: 0;
        top: 0;
        background-color: #f2f2f2;
        height: 100%;
        width: 60px;
        text-align: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }

     .page-builder-area-wrapper .all-field-wrap .action-wrap .add,
     .page-builder-area-wrapper .all-field-wrap .action-wrap .remove{
        display: inline-block;
        height: 30px;
        width: 30px;
        background-color: #339e4b;
        line-height: 30px;
        text-align: center;
        border-radius: 2px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
        cursor: pointer;
    }
     .page-builder-area-wrapper .all-field-wrap .action-wrap .remove {
        background-color: #bb4b4c;
    }

     .page-builder-area-wrapper .select-box-wrap select{
        border: 1px solid #e2e2e2;
    }


     .page-builder-area-wrapper .all-field-wrap ul li a {
        padding: 5px 15px;
        font-size: 14px;
    }

     .page-builder-area-wrapper .all-field-wrap input {
        font-size: 14px;
        height: 35px;
    }

     .page-builder-area-wrapper .all-field-wrap .tab-content {
        margin-top: 15px;
    }

     .page-builder-area-wrapper .all-field-wrap textarea {
        max-height: 60px;
    }

    .page-builder-area-wrapper .available-form-field li + li {
        margin-top: 10px;
    }
    .page-builder-area-wrapper .available-form-field li a {
        font-size: 12px;
    }
    /*.page-builder-area-wrapper .iconbox-repeater-wrapper {*/
    /*    background-color: #f2edf3;*/
    /*    padding: 0 20px 20px;*/
    /*    margin-bottom: 30px;*/
    /*}*/
    .page-builder-area-wrapper .widget-handler .content-part.show {
        visibility: visible;
        opacity: 1;
        height: auto;
        padding: 20px 30px 20px 26px;
    }
    .page-builder-area-wrapper .widget-handler .content-part {
        visibility: hidden;
        opacity: 0;
        height: 0;
    }
    .page-builder-area-wrapper .content-part select.form-control:not([size]):not([multiple])
    {
        height: 0;
        margin-bottom: 0;
    }
    .page-builder-area-wrapper .content-part  .nice-select.wide.form-control {
        margin-bottom: 0;
        height: 0;
    }
    .page-builder-area-wrapper .content-part.show  .nice-select.wide.form-control {
        margin-bottom: 20px;
        height: auto;
    }
    .page-builder-area-wrapper .content-part.show select.form-control:not([size]):not([multiple])
    {
        height: 40px;
        margin-bottom: 20px;
    }

    .page-builder-area-wrapper .content-part.show .nice-select.wide.form-control{
        display: block;
    }
    .page-builder-area-wrapper .content-part .nice-select.wide.form-control{
        display: none;
    }

    .page-builder-area-wrapper .sortable {
        border: 1px dashed #e2e2e2;
        padding: 30px;
    }

    .all-addons-wrapper ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
    }

    .all-addons-wrapper ul li {
        background-color: #f3f3f3;
    }

    .all-addons-wrapper ul li .top-part {
        font-size: 14px;
        line-height: 20px;
    }

    .sidebar-list-wrap .card {
        margin-bottom: 20px;
    }

    .all-widgets.available-form-field {
        border: 1px dashed #e2e2e2;
        padding: 10px;
    }

    .available-form-field li span.text-success {
        display: block;
        font-size: 12px;
        background-color: #d5ecd5;
        width: max-content;
        padding: 2px 10px;
        text-transform: capitalize;
        margin-top: 20px;
    }

    .all-addons-wrapper ul li .top-part .preview-image {
        position: absolute;
        right: 10px;
        top: 10px;
        width: 20px;
        height: 20px;
        background-color: #8880f9;
        text-align: center;
        color: #fff;
        font-size: 12px;
        cursor: pointer;
    }

    .sortable li div .form-group label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .sortable li .all-field-wrap .nav-item {
        padding: 5px 10px;
    }
    .available-form-field .range-wrap {
        display: flex;
    }

    .available-form-field .range-wrap .range-val {
        display: inline-block;
        width: 60px;
        height: 30px;
        background-color: #eee;
        margin-right: 0;
        margin-left: 10px;
        border-radius: 2px;
        color: #333;
        text-align: center;
        line-height: 30px;
        font-size: 14px;
        font-weight: 700;
    }
    .color_picker {
        display: inline-block;
        width: 35px;
        height: 35px;
        background-color: transparent;
        border: 5px solid #9b9b9b;
        box-shadow: 0 0 5px 0 rgba(0,0,0,0.05);
        cursor: pointer;
    }
    .ui-sortable .ui-sortable-placeholder {
        min-height: 40px;
        border: 1px dashed #e2e2e2;
        margin-bottom: 10px;
        visibility: visible !important;
        background-color: transparent;
    }
    .sortable li div .color_picker {
        background-color: white;
        display: block;
    }

    .page-builder-area-wrapper.extra-title {
        margin-bottom: 40px;
    }

    .page-builder-area-wrapper.extra-title .main-title {
        font-size: 18px;
        line-height: 28px;
        font-weight: 600;
        margin-bottom: 11px;
        background-color: #f3f3f3;
        padding: 10px;
    }

    .available-form-field li span.page-builder-info-text {
        display: block;
        font-size: 12px;
        line-height: 16px;
        font-weight: 400;
    }


    /* page builder */
    .all-addons-wrapper ul.ui-sortable li.widget-handler {
        position: relative;
        cursor: pointer;
    }

    .all-addons-wrapper ul.ui-sortable li.widget-handler .imageupshow {
        position: absolute;
        left: 0;
        max-width: 250px;
        height: auto;
        content: '';
        z-index: 9;
        bottom: 40px;
        border: 5px solid #fbfbfb;
        box-shadow: 0 0 15px 0 rgba(0,0,0,0.02);
        visibility: hidden;
        opacity: 0;
        transition: all 500ms;
    }

    .all-addons-wrapper ul.ui-sortable li.widget-handler:hover .imageupshow{
        visibility: visible;
        opacity: 1;
    }
    .available-form-field {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .all-widgets.available-form-field {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .all-widgets.available-form-field li {
        width: calc(100% / 2 - 10px) !important;
    }

    .available-form-field li {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 600;
    }

    .available-form-field li a {
        text-transform: capitalize;
    }

    .available-form-field li span {
        margin-right: 10px;
        text-transform: none;
    }

    .available-form-field li {
        position: relative;
        z-index: 0;
    }

    .available-form-field li .remove-fields {
        position: absolute;
        right: 0;
        top: 8px;
        color: red;
        cursor: pointer;
    }

    .available-form-field.main-fields .switch {
        width: 95px;
    }

    .nav-tabs {
        border-bottom: 1px solid #c7c7c7;
        margin-bottom: 30px;
    }

    button.icp.icp-dd.btn.btn-primary.dropdown-toggle.iconpicker-element {
        padding: 12px 20px;
    }

    button.btn.btn-primary.iconpicker-component {
        padding: 12px 20px;
    }

    .attachment-preview .thumbnail {
        overflow: hidden;
        position: static;
        transition: unset;
        width: 113px;
        height: 111px;
        background: #ddd;
    }

    .attachment-preview .thumbnail .centered {
        position: static;
        transform: initial;
        width: 130px;
        height: 130px;
    }

    .attachment-preview .thumbnail .centered img {
        transform: initial;
        max-width: 130px;
        max-height: 130px;
        height: 100%;
        width: 100%;
        text-align: center;
        transform: translate(-10%, -5%) !important;
    }

    .widget-handler .expand {
        position: absolute;
        right: 40px;
        width: 25px;
        height: 25px;
        line-height: 29px;
        background-color: #ffffff;
        border-radius: 50%;
        font-size: 12px;
        text-align: center;
        font-weight: 700;
        top: 10px;
        cursor: pointer;
    }

    .widget-handler .remove-widget {
        position: absolute;
        right: 10px;
        width: 25px;
        height: 25px;
        line-height: 29px;
        background-color: #dc3545;
        border-radius: 50%;
        font-size: 12px;
        text-align: center;
        font-weight: 700;
        top: 10px;
        cursor: pointer;
        color: #fff;
    }

    .available-form-field li {
        position: relative;
        z-index: 0;
        background: #fff8f8;
    }

    .all-addons-wrapper ul.ui-sortable li.widget-handler:hover .imageupshow img {
        max-width: 150px;
    }

</style>
