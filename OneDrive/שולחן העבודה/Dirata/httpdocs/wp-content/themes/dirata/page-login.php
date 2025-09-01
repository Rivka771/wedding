<?php
// Custom login page
get_header();
?>

<div class="form_wrapper login_form_wrapper position-relative overflow-hidden" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/bg01.svg);">
    <img class="form_shape img-fluid position-absolute" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02.svg" alt="bg02">
    <img class="form_shape_mobile" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg02_mini.svg" alt="bg02_mini">
    
    <div class="container">
        <div class="text-center">
            <h1 class="head_text">טוב לראות אותך שוב :)<br>שנתחבר?</h1>
            <span> 
                עוד לא נרשמת?
                <a href="/private-signup/">בחר אזור בלחיצה כאן </a>
                 

            </span>
        </div>
        
        <?php
        if (isset($_GET['login']) && $_GET['login'] === 'failed') {
            echo '<p class="error-message">⚠️ פרטי ההתחברות שגויים, נסה שוב!</p>';
        } elseif (isset($_GET['login']) && $_GET['login'] === 'empty') {
            echo '<p class="error-message">⚠️ נא למלא את כל השדות!</p>';
        }
        ?>

        <?php if (is_user_logged_in()) : ?>
            <p class="text-center">אתה כבר מחובר. <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">התנתק</a></p>
        <?php else : ?>
            <form method="post" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>">
                <input class="input_info" type="text" name="log" placeholder="מה האימייל?" required>
                
                <div class="password_info position-relative mt_30">
                    <input id="myInput" class="input_info" type="password" name="pwd" placeholder="סיסמה שקל לזכור:" required>
                    <span class="eye" onclick="myfunction()">
                        <i id="hide1" class="fas fa-eye"></i>
                        <i id="hide2" class="fas fa-eye-slash"></i>
                    </span>
                </div>

                <div class="text-left">
                    <a class="form_para mt_37" href="<?php echo esc_url(wp_lostpassword_url()); ?>">שכחת את הסיסמה?</a>
                </div>

                <button type="submit" name="wp-submit" class="form_btn mt_55">קליק וסיימנו  ←</button>
                <a class="google_btn mt_15" href="#">התחברות עם <span>Google</span></a>

                <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/חשבון-רשום/your-profile/')); ?>">
                <input type="hidden" name="testcookie" value="1">
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function myfunction() {
        var x = document.getElementById("myInput");
        var y = document.getElementById("hide1");
        var z = document.getElementById("hide2");

        if (x.type === 'password') {
            x.type = "text";
            y.style.display = "block";
            z.style.display = "none";
        } else {
            x.type = "password";
            y.style.display = "none";
            z.style.display = "block";
        }
    }
    window.myfunction = myfunction;
});
</script>

<style>
.error-message {
    color: red;
    text-align: center;
    font-size: 16px;
    margin-top: 15px;
    font-weight: bold;
}
</style>

<?php get_footer(); ?>