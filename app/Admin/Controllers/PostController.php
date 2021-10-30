<?php

namespace App\Admin\Controllers;

use App\Models\Post;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Post';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new Post());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('content', __('Content'))->display(function ($content) {
            return substr($content, '0', 100).'....';
        });
        $grid->column('author', __('Author'))->display(function (array $author) {
            return $author['username'] ?? null;
        });
        $grid->column('moderator', __('Moderator'))->display(function (array $moderator) {
            return $moderator['username'] ?? null;
        });
        $grid->column('moderated_at', __('Moderated at'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('content', __('Content'));
        $show->field('author', __('Author id'));
        $show->field('moderator', __('Moderator id'));
        $show->field('moderated_at', __('Moderated at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Post());

        $form->text('name', __('Name'));
        $form->textarea('content', __('Content'));
        $form->number('author', __('Author id'));
        $form->number('moderator', __('Moderator id'));
        $form->datetime('moderated_at', __('Moderated at'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
