<?php
/**
 * Template Name: Login & Register
 */

if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/shop' ) );
    exit;
}

$error   = '';
$success = '';
$tab     = isset( $_GET['tab'] ) && $_GET['tab'] === 'register' ? 'register' : 'login';

if ( isset( $_POST['action'] ) ) {
    check_admin_referer( 'auth_nonce' );

    if ( $_POST['action'] === 'login' ) {
        $creds = [
            'user_login'    => sanitize_text_field( $_POST['username'] ),
            'user_password' => $_POST['password'],
            'remember'      => true,
        ];
        $user = wp_signon( $creds, false );
        if ( is_wp_error( $user ) ) {
            $error = 'Wrong username or password.';
        } else {
            wp_redirect( home_url( '/shop' ) );
            exit;
        }

    } elseif ( $_POST['action'] === 'register' ) {
        $username = sanitize_user( $_POST['username'] );
        $email    = sanitize_email( $_POST['email'] );
        $password = $_POST['password'];

        if ( username_exists( $username ) ) {
            $error = 'Username already taken.';
            $tab   = 'register';
        } elseif ( email_exists( $email ) ) {
            $error = 'Email already registered.';
            $tab   = 'register';
        } elseif ( strlen( $password ) < 6 ) {
            $error = 'Password must be at least 6 characters.';
            $tab   = 'register';
        } else {
            $user_id = wp_create_user( $username, $password, $email );
            if ( is_wp_error( $user_id ) ) {
                $error = $user_id->get_error_message();
                $tab   = 'register';
            } else {
                wp_set_current_user( $user_id );
                wp_set_auth_cookie( $user_id );
                wp_redirect( home_url( '/shop' ) );
                exit;
            }
        }
    }
}

get_header();
?>
<div class="page-content">
<div class="auth-wrap">
  <div class="auth-tabs">
    <a href="?tab=login"    class="<?= $tab === 'login'    ? 'active' : '' ?>">Login</a>
    <a href="?tab=register" class="<?= $tab === 'register' ? 'active' : '' ?>">Register</a>
  </div>
  <div class="auth-body">
    <?php if ( $error )   echo "<div class='alert-error'>$error</div>"; ?>
    <?php if ( $success ) echo "<div class='alert-success'>$success</div>"; ?>

    <?php if ( $tab === 'login' ) : ?>
      <h2>Welcome back</h2>
      <form method="post">
        <?php wp_nonce_field( 'auth_nonce' ); ?>
        <input type="hidden" name="action" value="login">
        <div class="field"><label>Username</label><input type="text" name="username" required autofocus></div>
        <div class="field"><label>Password</label><input type="password" name="password" required></div>
        <button class="btn-primary" type="submit">Log in</button>
      </form>

    <?php else : ?>
      <h2>Create account</h2>
      <form method="post">
        <?php wp_nonce_field( 'auth_nonce' ); ?>
        <input type="hidden" name="action" value="register">
        <div class="field"><label>Username</label><input type="text" name="username" required autofocus></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" required></div>
        <button class="btn-primary" type="submit">Create account</button>
      </form>
    <?php endif; ?>
  </div>
</div>
</div>

<?php get_footer(); ?>