<?php

namespace shop\useCases\manage\blog;

use shop\forms\manage\blog\post\CommentEditForm;
use shop\repositories\blog\PostRepository;

class CommentManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function edit($postId, $id, CommentEditForm $form): void
    {
        $post = $this->posts->get($postId);
        $post->editComment($id, $form->parentId, $form->text);
        $this->posts->save($post);
    }

    public function activate($postId, $id): void
    {
        $post = $this->posts->get($postId);
        $post->activateComment($id);
        $this->posts->save($post);
    }

    public function remove($postId, $id): void
    {
        $post = $this->posts->get($postId);
        $post->removeComment($id);
        $this->posts->save($post);
    }
}