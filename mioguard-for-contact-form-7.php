<?php

/**
 * Plugin Name: MioGuard for Contact Form 7 
 * Plugin URI: https://github.com/Seconet/MioGuard
 * Description:  Rate limit e honeypot per moduli Contact Form 7. Plugin non affiliato al team ufficiale CF7.
 * Version: 1.0.2
 * Author: Seconet - Sergio Cornacchione
 * Author URI: https://seconet.it
 * Text Domain: mioguard-for-contact-form-7
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: contact-form-7
 * Note: Uses WordPress transients with automatic expiration.No database cleanup or cron jobs required.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * =========================
 * RATE LIMIT + HONEYPOT
 * =========================
 */
add_filter('wp_mioguard__validate', function ($result) {

    if (! isset($_SERVER['REMOTE_ADDR'])) {
        return $result;
    }


    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';

    /**
     * RATE LIMIT
     */
    $minutes = get_option('mioguardsg_rate_limit', 5);
    $minutes = absint($minutes);

    // hard clamp di sicurezza
    if ($minutes < 1) {
        $minutes = 1;
    } elseif ($minutes > 1440) {
        $minutes = 1440;
    }

    $key   = 'mioguardsg_' . md5($ip);
    $limit = $minutes * 60;

    if (get_transient($key)) {
        $result->invalidate(
            '',
            __('Hai già inviato un messaggio di recente. Riprova più tardi.', 'mioguard-for-contact-form-7')
        );
        return $result;
    }

    /**
     * HONEYPOT
     */
    $honeypot_field = get_option('mioguardsg_honeypot_field', 'company_fax');

    // sanitize extra difensivo
    $honeypot_field = preg_replace('/[^a-zA-Z0-9_]/', '', $honeypot_field);

    // Honeypot value (MioGuard already handles nonce verification)
    /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
    $honeypot_value = isset($_POST[$honeypot_field]) ? sanitize_text_field(wp_unslash($_POST[$honeypot_field])) : '';

    if ($honeypot_field && $honeypot_value !== '') {
        $result->invalidate(
            '',
            __('Invio non consentito.', 'mioguard-for-contact-form-7')
        );
        return $result;
    }

    // set transient SOLO se passa tutti i controlli
    set_transient($key, time(), $limit);

    return $result;
}, 10, 1);



/**
 * =========================
 * ADMIN SETTINGS
 * =========================
 */
add_action('admin_menu', function () {
    add_options_page(
        'MioGuard',
        'MioGuard',
        'manage_options',
        'mioguard-for-contact-form-7',
        'mioguardsg_settings_page'
    );
});

add_action('admin_init', function () {

    register_setting(
        'mioguardsg_settings_group',
        'mioguardsg_rate_limit',
        [
            'type'              => 'integer',
            'sanitize_callback' => function ($value) {
                $value = absint($value);
                if ($value < 1) {
                    return 1;
                }
                if ($value > 1440) {
                    return 1440;
                }
                return $value;
            },
            'default' => 5,
        ]
    );

    register_setting(
        'mioguardsg_settings_group',
        'mioguardsg_honeypot_field',
        [
            'type'              => 'string',
            'sanitize_callback' => function ($value) {

                $value = trim($value);

                // se vuoto → fallback sicuro
                if ($value === '') {
                    return 'company_fax';
                }

                // rimuove caratteri non consentiti
                $value = preg_replace('/[^a-zA-Z0-9_]/', '', $value);

                // se dopo la pulizia è diventato vuoto → fallback
                if ($value === '') {
                    return 'company_fax';
                }

                return $value;
            },
            'default' => 'company_fax',
        ]
    );
    register_setting(
        'mioguardsg_settings_group',
        'mioguard_show_badge',
        [
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 0,
        ]
    );
});



function mioguardsg_settings_page()
{
?>
    <div class="wrap">
        <h1>MioGuard</h1>

        <form method="post" action="options.php">
            <?php settings_fields('mioguardsg_settings_group'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Rate limit (minuti)', 'mioguard-for-contact-form-7'); ?></th>
                    <td>
                        <input
                            type="number"
                            name="mioguardsg_rate_limit"
                            value="<?php echo esc_attr(get_option('mioguardsg_rate_limit', 5)); ?>"
                            min="1"
                            max="1440">
                        <p class="description">
                            <?php esc_html_e('Minimum 1, maximum 1440 (24h)', 'mioguard-for-contact-form-7'); ?>
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e('Nome campo Honeypot (CF7)', 'mioguard-for-contact-form-7'); ?></th>
                    <td>
                        <input
                            type="text"
                            name="mioguardsg_honeypot_field"
                            value="<?php echo esc_attr(get_option('mioguardsg_honeypot_field', 'company_fax')); ?>">
                        <p class="description">
                            <?php esc_html_e('Usa lo stesso nome nel modulo CF7 (es: <code>[text company_fax]</code>)', 'mioguard-for-contact-form-7'); ?>
                            <br />
                            <?php esc_html_e('Se il campo è vuoto o non valido, verrà usato automaticamente company_fax', 'mioguard-for-contact-form-7'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                       <?php esc_html_e('Show “Protected by MioGuard” badge', 'mioguard-for-contact-form-7'); ?>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            name="mioguard_show_badge"
                            value="1"
                            <?php checked(1, get_option('mioguard_show_badge', 0)); ?>>
                        <p class="description">
                            <?php esc_html_e('Se selezionato, mostrerà un piccolo badge sotto i moduli CF7 protetti dal plugin.', 'mioguard-for-contact-form-7'); ?>
                        </p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

add_filter('wpmioguard__form_elements', function ($content) {
    if (get_option('mioguard_show_badge', 0)) {
        $badge = '<div class="mioguardsg-badge">'. esc_html__('Protected by MioGuard', 'mioguard-for-contact-form-7') . '</div>';
        $content .= $badge;
    }
    return $content;
});

add_action('wp_enqueue_scripts', 'mioguardsg_enqueue_styles');

function mioguardsg_enqueue_styles()
{

    if (! get_option('mioguard_show_badge', 0)) {
        return;
    }

    // Registra uno style "vuoto"
    wp_register_style(
        'mioguardsg-inline-style',
        false,
        [],
        '1.0.2'
    );

    wp_enqueue_style('mioguardsg-inline-style');

    $css = '
    .mioguardsg-badge {
        display: inline-flex !important;
        align-items: center;
        font-size: 0.75em;
        color: #555;
        margin-top: 10px !important;
        font-family: sans-serif;
    }
    .mioguardsg-badge::before {
        content: "";
        display: inline-block !important;
        width: 22px;
        height: 22px;
        margin-right: 5px;
        background-color: #00aa09;
        clip-path: polygon(90% 47%, 72% 57%, 81% 75%, 63% 68%, 45% 75%, 52% 57%, 36% 46%, 54% 44%, 61% 24%, 72% 44%);
    }';

    wp_add_inline_style('mioguardsg-inline-style', $css);
}
