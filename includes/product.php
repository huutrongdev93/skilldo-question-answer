<?php
class QuestionProduct {
    public static function setting(): void
    {
        $productQuestion  = Option::get('product_question', array(
            'position' => 'disabled',
            'data'     => 'handmade',
            'limit'    => 12,
        ));

        $form = form();

        $form->select2('product_question[position]', [
            'content' => 'Nội dung sản phẩm',
            'bottom'  => 'Footer sản phẩm',
            'disabled' => 'Tắt',
        ], [
            'label' => 'Vị trí câu hỏi',
            'start' => 6
        ], (!empty($productQuestion['position'])) ? $productQuestion['position'] : 'sidebar' );

        $form->select2('product_question[data]', [
            'auto' => 'Tự động random',
            'handmade'  => 'Thủ công chọn từng câu hỏi',
        ], [
            'label' => 'Dữ liệu câu hỏi',
            'start' => 6
        ], (!empty($productQuestion['data'])) ? $productQuestion['data'] : 'handmade' );

        $form->range('product_question[limit]', [
            'label' => 'Số câu hỏi hiển thị',
            'min' => 1,
            'max' => 30
        ], (!empty($productQuestion['limit'])) ? $productQuestion['limit'] : '' );

        Admin::view('components/system-default', [
            'title'           => 'Câu hỏi liên quan',
            'description'     => 'Cấu hình hiển thị các câu hỏi liên quan sản phẩm',
            'form'            => $form
        ]);
    }

    public static function product($form, $object) {

        $question = (have_posts($object)) ? Product::getMeta($object->id, 'product_question', true) : [];

        $form->popoverAdvance('product_question', [
            'label'     => 'Câu hỏi liên quan',
            'search'    => 'post',
            'taxonomy'  => QA_KEY,
            'noImage'   => true,
        ], $question);

        return $form;
    }
    static public function productSave($product_id, $module): void
    {
        if($module == 'products') {

            $question = request()->input('product_question');

            if(is_string($question)) $question = [];

            Product::updateMeta($product_id, 'product_question', $question);
        }
    }

    public static function html($object) {

        if(Template::getData('object') === false) return;

        $productCurrent = Template::getData('object');

        $productQuestion = Option::get('product_question');

        $args = [
            'data'      => (!empty($productQuestion['data'])) ?  $productQuestion['data'] : 'handmade',
            'limit' 	=> (!empty($productQuestion['limit'])) ?  $productQuestion['limit'] : 12,
            'position' 	=> (!empty($productQuestion['position'])) ?  $productQuestion['position'] : 'sidebar',
        ];

        if($args['data'] == 'handmade') {

            $questionsData =  (have_posts($productCurrent)) ? Product::getMeta($productCurrent->id, 'product_question', true) : [];

            if(!have_posts($questionsData)) return false;

            $questionsQr = Qr::set('post_type', QA_KEY)->whereIn('id', $questionsData)->limit($args['limit'])->orderByRaw('rand()');
        }
        else {
            $questionsQr = Qr::set('post_type', QA_KEY)->limit($args['limit'])->orderByRaw('rand()');
        }

        $questions = Posts::gets($questionsQr);

        if(have_posts($questions)) {

            $args['questions']  = $questions;

            $args['heading']    = __('Câu Hỏi Liên Quan', 'product_heading_question');

            $args['id']         = 'question';

            if($args['position'] == 'content' || $args['position'] == 'bottom') {
                Plugin::view(QA_NAME, 'question-content', $args);
            }
        }
    }
}

add_action('admin_product_setting_detail', 'QuestionProduct::setting', 40);
add_filter('admin_product_field_related', 'QuestionProduct::product', 40, 2);
add_action('save_object', 'QuestionProduct::productSave', 10, 2);

if(Template::isPage('products_detail')) {
    $productQuestion = Option::get('product_question');
    $productQuestion = (empty($productQuestion['position'])) ? 'sidebar' : $productQuestion['position'];
    if($productQuestion == 'sidebar') add_action('product_detail_sidebar','QuestionProduct::html', 30);
    if($productQuestion == 'content') add_action('product_detail_tabs','QuestionProduct::html', 40);
    if($productQuestion == 'bottom')  add_action('product_detail_after','QuestionProduct::html', 40);
}