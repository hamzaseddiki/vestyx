<style>
    /*(Frontend Switcher Css)*/

    /* Switch css*/

    .switch {
        position: relative;
        display: block;
        width: 86px;
        height: 34px;
    }

    input:checked + .slider,
    input:checked + .slider-yes-no {
        background-color: #2196F3;
    }

    .slider,
    .slider-yes-no {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider:before,
    input:checked + .slider-yes-no:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    input:checked + .slider:before {
        content: "Show";
    }

    input:checked + .slider-yes-no:before {
        content: "Yes";
    }

    .slider:before {
        position: absolute;
        content: "Hide";
        height: 26px;
        width: 50px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        text-align: center;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        line-height: 26px;
    }

    .slider-yes-no:before {
        position: absolute;
        content: "No";
        height: 26px;
        width: 50px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        text-align: center;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        line-height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    input[type=checkbox], input[type=radio] {
        box-sizing: border-box;
        padding: 0;
    }

</style>
