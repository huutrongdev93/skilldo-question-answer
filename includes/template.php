<?php

use SkillDo\Validate\Rule;

class QuestionTemplate {

    static function list(): void
    {
        $categories = PostCategory::gets(Qr::set('cate_type', 'question-answer-category'));

        if(have_posts($categories)) {

            foreach ($categories as $category) {
                $category->objects = Posts::gets(Qr::set('post_type', QA_KEY)->whereByCategory($category));
            }

            Plugin::view(QA_NAME, 'list/question-category', [
                'categories' => $categories
            ]);
        }
        else {

            $objects = Posts::gets(Qr::set('post_type', QA_KEY));

            Plugin::view(QA_NAME, 'list/question-object', [
                'objects' => $objects
            ]);
        }

    }

    static function form(): \SkillDo\Form\Form
    {
        $form = form();

        $form->setFormId('js_question_form');

        $form->setIsValid(true);

        $form->setCallbackValidJs('questionFormSubmit');

        $form->text('name', [
            'label' => trans('question.form.field.fullname'),
            'placeholder' => trans('question.form.field.fullname.placeholder'),
            'validations' => Rule::make()->notEmpty()
        ]);
        $form->email('email', [
            'label' => trans('email'),
            'placeholder' => trans('question.form.field.email.placeholder'),
            'validations' => Rule::make()->notEmpty()->email()
        ]);
        $form->textarea('question', [
            'label' => trans('question.form.field.content'),
            'placeholder' => trans('question.form.field.content.placeholder'),
            'cols' => 30,
            'rows' => 10,
            'validations' => Rule::make()->notEmpty()->string()->min(20)
        ]);

        return $form;
    }

    static function formRender(): void
    {
        $form = QuestionTemplate::form();

        Plugin::view(QA_NAME, 'list/question-form', [
            'form' => $form
        ]);
    }
}

add_action('question_page_index', 'QuestionTemplate::list', 20);
add_action('question_page_index', 'QuestionTemplate::formRender', 30);