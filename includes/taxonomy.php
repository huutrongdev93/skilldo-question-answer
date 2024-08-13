<?php
Class QA_Taxonomy {

    function __construct() {
        $this->register();
        add_filter('manage_post_'.QA_KEY.'_columns', array($this, 'columnHeader'), 10);
        add_filter('manage_post_'.QA_KEY.'_custom_column', array($this, 'columnData'), 10, 2);
        add_filter('manage_post_question_columns', array($this, 'columnQuestionHeader'), 10);
        add_filter('single_row_post_question', array($this, 'singleQuestionRow'), 10, 2);
        add_action('admin_footer', array($this, 'updateCountQuestion'), 10);
    }

    public function register(): void
    {
        $count = \SkillDo\Cache::get('question_count_new');

        if(Admin::is() && !request()->ajax() && is_null($count)) {

            $count = Posts::count(Qr::set('post_type', 'question')->where('status', 1));

            \SkillDo\Cache::save('question_count_new', $count);
        }

        Taxonomy::addPost(QA_KEY, [
            'labels' => [
                'name'          => __('Hỏi đáp'),
                'singular_name' => __('Hỏi đáp'),
            ],
            'show_in_nav_menus'  => false,
            'show_in_nav_admin'  => true,
            'menu_icon' => '<img src="'.QA_PATH.'/assets/images/icon.png" alt="">',
            'supports' => array(
                'group' => array('info'),
                'field' => array('title', 'content')
            ),
            'capabilities' => array(
                'view'      => 'view_question_answer',
                'add'       => 'add_question_answer',
                'edit'      => 'edit_question_answer',
                'delete'    => 'delete_question_answer',
            ),
            'count' => $count
        ]);

        Taxonomy::addCategory('question-answer-category', QA_KEY, [
            'labels' => [
                'name'      => 'Nhóm hỏi đáp',
                'singular'  => 'Nhóm hỏi đáp',
            ],
            'public' => true,
            'show_in_nav_admin' => true,
            'show_in_nav_menus' => false,
            'parent' => false,
        ]);

        Taxonomy::addPost('question', [
            'labels' => array(
                'name'          => __('Câu hỏi'),
                'singular_name' => __('Câu hỏi'),
            ),
            'show_in_nav_menus'  => false,
            'show_in_nav_admin'  => false,
            'supports' => array(
                'group' => array('info'),
                'field' => array('title', 'content')
            ),
            'capabilities' => array(
                'view'      => 'view_question',
                'add'       => 'add_question',
                'edit'      => 'edit_question',
                'delete'    => 'delete_question',
            ),
        ]);

        if(Auth::hasCap('view_question')) {
            AdminMenu::addSub(QA_KEY, 'question', 'Câu hỏi', 'post/?post_type=question', ['count' => $count]);
        }
    }

    public function columnHeader( $columns ): array
    {
        $columnsNew = [];

        foreach ($columns as $key => $value) {

            if($key == 'image') continue;

            $columnsNew[$key] = $value;
        }

        return $columnsNew;
    }

    public function columnData( $column_name, $item ): void
    {
        switch ( $column_name ) {
            case 'taxonomy-question-answer-category':
                $str = '';
                $categories = PostCategory::getsByPost($item->id, Qr::set('value', 'question-answer-category')->select('categories.id', 'categories.name', 'categories.slug'));
                foreach ($categories as $value) {
                    $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/post/post_categories/edit/'.$value->id.'?cate_type='.Admin::getCateType(), $value->name);
                }
                echo trim($str,', ');
            break;
        }
    }

    public function columnQuestionHeader( $columns ): array
    {
        return [
            'cb'        => 'cb',
            'name'      => [
                'label' => 'Họ tên',
                'columns' => fn ($item, $args) => \SkillDo\Table\Columns\ColumnText::make('title', $item, $args)
            ],
            'email'      => [
                'label' => 'Email',
                'columns' => fn ($item, $args) => \SkillDo\Table\Columns\ColumnText::make('excerpt', $item, $args)
            ],
            'question'      => [
                'label' => 'Câu hỏi',
                'columns' => fn ($item, $args) => \SkillDo\Table\Columns\ColumnText::make('content', $item, $args)
            ],
            'action'    => 'Hành động',
        ];
    }

    public function singleQuestionRow( $columns, $item ): string
    {
        return '<tr class="tr_'.$item->id.' '.(($item->status == 1) ? 'new' : '').'">';
    }

    public function updateCountQuestion(): void
    {
        if(Template::isPage('post_index') && Admin::getPostType() == 'question') {
            $model = model('post');
            $model->update(['status' => 0], Qr::set('post_type', 'question')->where('status',1));
        }
    }
}

