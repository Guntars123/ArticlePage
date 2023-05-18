<?php declare(strict_types=1);

namespace App\Commands;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

class ConsoleRenderer
{
    public function renderSingleArticle(Article $article, array $comments): void
    {
        echo "-------------------------------------" . PHP_EOL;
        echo 'Author: ' . $article->getAuthor()->getName() . PHP_EOL;
        echo '«---' . ucfirst($article->getTitle()) . '---»' . PHP_EOL;
        echo ucfirst($article->getBody()) . PHP_EOL;
        echo "-------------------------------------" . PHP_EOL;
        echo 'COMMENTS(' . count($comments) . '):' . PHP_EOL;
        echo "-------------------------------------" . PHP_EOL;
        foreach ($comments as $comment) {
            /** @var Comment $comment */
            echo 'Commentator name: ' . ucfirst($comment->getName()) . PHP_EOL;
            echo ucfirst($comment->getBody()) . PHP_EOL;
            echo "-------------------------------------" . PHP_EOL;
        }
    }

    public function renderArticles(array $articles): void
    {
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" . PHP_EOL;
        foreach ($articles as $article) {
            /** @var Article $article */
            echo "-------------------------------------" . PHP_EOL;
            echo 'Author: ' . $article->getAuthor()->getName() . PHP_EOL;
            echo '«---' . ucfirst($article->getTitle()) . '---»' . PHP_EOL;
            echo ucfirst($article->getBody()) . PHP_EOL;
            echo "-------------------------------------" . PHP_EOL;
        }
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" . PHP_EOL;
    }

    public function renderSingleUser(User $user, array $articles): void
    {
        echo "-------------------------------------" . PHP_EOL;
        echo 'Info about ' . $user->getUsername() . ':' . PHP_EOL;
        echo "Picture []" . PHP_EOL;
        echo 'Name: ' . $user->getName() . PHP_EOL;
        echo 'Email: ' . $user->getEmail() . PHP_EOL;
        echo 'Address: ' . $user->getAddress()->street . ', ' .
            $user->getAddress()->suite . ', ' . $user->getAddress()->city . ', ' .
            $user->getAddress()->zipcode . PHP_EOL;
        echo 'Phone: ' . $user->getPhone() . PHP_EOL;
        echo 'Website: ' . $user->getWebsite() . PHP_EOL;
        echo 'Company: ' . $user->getCompany()->name . PHP_EOL;
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" . PHP_EOL;
        echo $user->getUsername() . ' posts(' . count($articles) . '):' . PHP_EOL;
        foreach ($articles as $article) {
            /** @var Article $article */
            echo "-------------------------------------" . PHP_EOL;
            echo 'Article picture: []' . PHP_EOL;
            echo '«---' . ucfirst($article->getTitle()) . '---»' . PHP_EOL;
            echo ucfirst($article->getBody()) . PHP_EOL;
        }
    }

    public function renderUsers(array $users): void
    {
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" . PHP_EOL;
        echo 'Meet and find more about our users:' . PHP_EOL;
        foreach ($users as $user) {
            /** @var User $user */
            echo "-------------------------------------" . PHP_EOL;
            echo "Picture []" . PHP_EOL;
            echo 'User: ' . $user->getUserName() . PHP_EOL;
            echo 'User ID: ' . $user->getId() . PHP_EOL;
            echo "-------------------------------------" . PHP_EOL;
        }
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" . PHP_EOL;
    }

}
