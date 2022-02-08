<?php 
add_action( 'init', 'callbackplugin_setup_post_type' );
function callbackplugin_setup_post_type(){
	// Регистрируем тип записи "book"
	register_post_type('callback', array(
		'labels'             => array(
			'name'               => 'Обратная связь', // Основное название типа записи
			'singular_name'      => 'Обратная связь', // отдельное название записи типа Book
			'add_new'            => 'Добавить новую',
			'add_new_item'       => 'Добавить новую',
			'edit_item'          => 'Редактировать',
			'new_item'           => 'Новая книга',
			'view_item'          => 'Посмотреть',
			'search_items'       => 'Найти',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'В корзине не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Обратная связь'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	) );
}
function myplugin_install(){
	// Запускаем функцию регистрации типа записи
	callbackplugin_setup_post_type();

	// Сбрасываем настройки ЧПУ, чтобы они пересоздались с новыми данными
	flush_rewrite_rules();
}

## Добавляем блоки в основную колонку на страницах постов и пост. страниц
add_action('add_meta_boxes', 'callbackplugin_add_custom_box');
function callbackplugin_add_custom_box(){
	$screens = array( 'callback');
	add_meta_box( 'callback_sectionid', 'Контактные данные', 'callbackplugin_meta_box_callback', $screens );
}

// HTML код блока
function callbackplugin_meta_box_callback( $post, $meta ){
	$screens = $meta['args'];

	// Используем nonce для верификации
	wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );

	// значение поля
	$name_value = get_post_meta( $post->ID, 'name_callback', 1 );
    $tel_value = get_post_meta( $post->ID, 'tel_callback', 1 );
    $email_value = get_post_meta( $post->ID, 'email_callback', 1 );
    $date_callback = get_post_meta( $post->ID, 'date_callback', 1 );

	?>
    <div class="callback_input_container">
        <div class="callback_input_item">
            <label for="name_callback_field">Имя</label> 
            <input type="text" id="name_callback_field" name="name_callback" value="<?php echo $name_value; ?>" size="25" />
        </div>
        <div class="callback_input_item">
            <label for="tel_callback_field">Email</label>
            <input type="text" id="tel_callback_field" name="tel_callback" value="<?php echo $email_value; ?>" size="25" />
        </div>
        <div class="callback_input_item">
            <label for="email_callback_field">Телефон</label>
            <input type="text" id="email_callback_field" name="email_callback" value="<?php echo $tel_value; ?>" size="25" />
        </div>
        <div class="callback_input_item">
            <label for="date_callback_field">Дата</label>
            <input type="text" id="date_callback_field" name="date_callback" value="<?php echo $date_callback; ?>" size="25" readonly/>
        </div>
    </div>
    <?php
}

add_action( 'save_post', 'myplugin_save_postdata' );
function myplugin_save_postdata( $post_id ) {
	// Убедимся что поле установлено.
	if ( ! isset( $_POST['name_callback'] ) ){
        return;
    }
    if ( ! isset( $_POST['tel_callback'] ) ){
        return;
    }
    if ( ! isset( $_POST['email_callback'] ) ){
        return;
    }
    if ( ! isset( $_POST['date_callback'] ) ){
        return;
    }
		

	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	if ( ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
		return;

	// если это автосохранение ничего не делаем
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;

	// проверяем права юзера
	if( ! current_user_can( 'edit_post', $post_id ) )
		return;

	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля input.
	$name_callback_data = sanitize_text_field( $_POST['name_callback'] );
    $tel_callback_data = sanitize_text_field( $_POST['tel_callback'] );
    $email_callback_data = sanitize_text_field( $_POST['email_callback'] );
    $date_callback_data = sanitize_text_field( $_POST['date_callback'] );

	// Обновляем данные в базе данных.
	update_post_meta( $post_id, 'name_callback', $name_callback_data );
    update_post_meta( $post_id, 'tel_callback', $tel_callback_data );
    update_post_meta( $post_id, 'email_callback', $email_callback_data );
    update_post_meta( $post_id, 'date_callback', $date_callback_data );
}
?>