<div class="container">
    <div class="wrap">
        @do_action('question_page_index');
    </div>
</div>
<style>
    body {
        -webkit-animation: bugfix infinite 1s;
    }
    @-webkit-keyframes bugfix {
        from {
            padding: 0;
        }
        to {
            padding: 0;
        }
    }

    .wrap {
        position: relative;
        width: 780px;
        max-width: 100%;
        margin: 0 auto;
    }
    .question-section h3 {
        font-size: 30px; font-weight: bold;
        margin-top: 30px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--theme-color);
        display: inline-block;
    }
    .question-section .question-wrap {
        width: 100%;
        border-bottom: 1px solid rgba(238,238,238, 0.5);
        padding: 30px 0 0 0;
    }
    .question-section .question-wrap a {
        cursor: pointer;
        display: block;
        margin-bottom: 0;
        -webkit-transition: all 0.55s;
        transition: all 0.55s;
        color: #000;
        font-weight: bold;
        font-size: 20px;
        line-height: 1.4;
        text-transform: none;
        padding-bottom: 30px;
    }
    .question-section .question-wrap a:hover {
        color:var(--theme-color);
    }
    .question-section .question-wrap .collapse  {
        font-size: 16px!important; line-height: 26px!important;
    }
    .question-section .question-wrap .collapse.show {
        padding-bottom: 30px;
    }

    .question-section-form {
        margin-top: 30px;
    }
    .question-section-form .header-title h3 {
        margin-top: 30px;
        margin-bottom: 30px;
        font-size: 40px;
    }
    .question-section-form form {
        position: relative;
        width: 500px;
        max-width: 100%;
        box-shadow: 0px 15px 35px rgb(0 0 0 / 10%);
        padding: 14px;
        border-radius: 10px;
        margin: 0 auto 20px;
        background-color: #fff;
    }
    .question-section-form label {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .question-section-form .form-control {
        margin-bottom: 10px;
    }
</style>