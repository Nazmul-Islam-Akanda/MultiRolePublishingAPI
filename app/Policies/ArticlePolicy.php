<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    public function create(User $user)
    {
        return $user->hasRole('author');
    }
    public function update(User $user, Article $article)
    {
        return $user->id === $article->user_id && $user->hasRole('author');
    }

    public function publish(User $user, Article $article)
    {
        return $user->hasRole('editor') || $user->hasRole('admin');
    }

    public function delete(User $user, Article $article)
    {
        return $user->hasRole('admin');
    }

}
