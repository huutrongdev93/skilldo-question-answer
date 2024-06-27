<?php
class QA_Activator {

    public static function activate(): void
    {
        self::addRole();
        self::randomData();
        self::templateFile();
    }

    public static function randomData(): void
    {
        $questions = [
            [
                'title'     => 'What are some random questions to ask?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'Do you include common questions?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'Can I use this for 21 questions?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'Are these questions for girls or for boys?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'What is the next skill that you\'d like to learn really well?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'How would you describe someone who is wealthy?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
        ];

        foreach ($questions as $question) {
            $question['post_type'] = QA_KEY;
            Posts::insert($question);
        }

        $count = Pages::where('slug', 'faq')->amount();

        if($count == 0) {
            Pages::insert(['title' => 'FAQ']);
        }

        $product_question = Option::get('product_question');

        if(empty($product_question)) {
            Option::update('product_question', array(
                'position' => 'disabled',
                'data'     => 'handmade',
                'limit'    => 12,
            ));
        }
    }

    public static function templateFile(): void
    {
        $store = Storage::disk('views');

        $templateLayout  = [
            'template-page-faq.blade.php'  => 'plugins/'.QA_NAME.'/template/template-page-faq.blade.php',
        ];

        foreach ($templateLayout as $file_name => $file_path) {
            if($store->has(Theme::name().'/theme-child/layouts/'.$file_name)) {
                continue;
            }
            $store->copy($file_path, Theme::name().'/theme-child/layouts/'.$file_name);
        }

        $templateViews  = [
            'page-faq.blade.php' => 'plugins/'.QA_NAME.'/template/page-faq.blade.php',
        ];

        foreach ($templateViews as $file_name => $file_path) {
            if($store->has(Theme::name().'/theme-child/'.$file_name)) {
                continue;
            }
            $store->copy($file_path, Theme::name().'/theme-child/'.$file_name);
        }
    }

    public static function addRole(): void
    {
        // Add caps for Root role
        $role = Role::get('root');
        $role->add('view_question_answer');
        $role->add('add_question_answer');
        $role->add('edit_question_answer');
        $role->add('delete_question_answer');

        $role->add('view_question');
        $role->add('delete_question');

        // Add caps for Administrator role
        $role = Role::get('administrator');
        $role->add('view_question_answer');
        $role->add('add_question_answer');
        $role->add('edit_question_answer');
        $role->add('delete_question_answer');

        $role->add('view_question');
        $role->add('delete_question');
    }
}