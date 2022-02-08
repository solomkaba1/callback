<?php
if( isset( $_POST['name_callback'] ) && isset( $_POST['tel_callback'] ) && isset( $_POST['email_callback'] ) ){
    if( !empty( $_POST['name_callback'] ) && !empty( $_POST['tel_callback'] ) && !empty( $_POST['email_callback'] ) ){
        $name_callback = sanitize_text_field( $_POST['name_callback'] );
        $tel_callback = sanitize_text_field( $_POST['tel_callback'] );
        $email_callback = sanitize_text_field( $_POST['email_callback'] );
        $date_callback = current_time( 'd-m-Y H:i:s', 0 );
        $post_data = array(
            'post_title'    => 'Заявка от '.$name_callback,
            'post_status'   => 'draft',
            'post_type'     => 'callback',
            'meta_input'    => [ 
                'name_callback'     => $name_callback,
                'tel_callback'      => $tel_callback,
                'email_callback'    => $email_callback,
                'date_callback'     => $date_callback,
            ],
        );
        
        // Вставляем запись в базу данных
        $post_id = wp_insert_post( $post_data );
    }
}
add_shortcode( 'callback_code', 'callback_func' );

function callback_func(){
    $currenturl = get_permalink();
    ?>
    <form action="<?php echo $currenturl; ?>" method="post">
        <input type="text" name="name_callback" placeholder="Ваше имя" required >
        <input type="tel" name="tel_callback" placeholder="Ваш телефон" required >
        <input type="email" name="email_callback" placeholder="Ваш Email" required >
        <button type="submit">Отправить</button>
    </form>

    <?php
}


?>
