
<style>









    /* Demo Styles */
    #content {
        margin: 0 auto;
        padding-bottom: 50px;
        width: 80%;
        max-width: 978px;
    }

    h1 {
        font-size: 40px;
    }

    /* The Loader */
    #loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        overflow: hidden;
    }
    .no-js #loader-wrapper {
        display: none;
    }

    #loader {
        display: block;
        position: relative;
        left: 50%;
        top: 50%;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: var(--main-color-one);
        -webkit-animation: spin 1.7s linear infinite;
        animation: spin 1.7s linear infinite;
        z-index: 11;
    }
    #loader:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: var(--main-color-two);
        -webkit-animation: spin-reverse 0.6s linear infinite;
        animation: spin-reverse 0.6s linear infinite;
    }
    #loader:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: var(--main-color-three);
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    @-webkit-keyframes spin-reverse {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(-360deg);
        }
    }
    @keyframes spin-reverse {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(-360deg);
        }
    }
    #loader-wrapper .loader-section {
        position: fixed;
        top: 0;
        width: 51%;
        height: 100%;
        background: rgba(255, 255, 255, 0.29);
        z-index: 10;
    }

    #loader-wrapper .loader-section.section-left {
        left: 0;
    }

    #loader-wrapper .loader-section.section-right {
        right: 0;
    }

    /* Loaded styles */
    .loaded #loader-wrapper .loader-section.section-left {
        transform: translateX(-100%);
        transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
    }

    .loaded #loader-wrapper .loader-section.section-right {
        transform: translateX(100%);
        transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
    }

    .loaded #loader {
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .loaded #loader-wrapper {
        visibility: hidden;
        transform: translateY(-100%);
        transition: all 0.3s 1s ease-out;
    }
    #loader-wrapper {
        display: none;
    }






    .preloaderBox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #1a237e;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 4px 4px 20px rgba(0, 0, 0, 0.3);
    }

    .preloaderInner {
        height: 15px;
        width: 105px;
        display: flex;
        position: relative;
    }
    .preloaderInner .circle {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background-color: #fff;
        animation: move 500ms linear 0ms infinite;
        margin-right: 30px;
    }
    .preloaderInner .circle:first-child {
        position: absolute;
        top: 0;
        left: 0;
        animation: grow 500ms linear 0ms infinite;
    }
    .preloaderInner .circle:last-child {
        position: absolute;
        top: 0;
        right: 0;
        margin-right: 0;
        animation: grow 500ms linear 0s infinite reverse;
    }

    @keyframes grow {
        from {
            transform: scale(0, 0);
            opacity: 0;
        }
        to {
            transform: scale(1, 1);
            opacity: 1;
        }
    }
    @keyframes move {
        from {
            transform: translateX(0px);
        }
        to {
            transform: translateX(45px);
        }
    }

</style>


<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

