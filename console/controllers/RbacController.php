<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init.
 */
class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // удаляем старые данные из бд
        $auth->removeAll();

        // создаем роли
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $seller = $auth->createRole('seller');
        $seller->description = 'Продавец';
        $customer = $auth->createRole('customer');
        $customer->description = 'Покупатель';

        $auth->add($admin);
        $auth->add($seller);
        $auth->add($customer);

        // добавляем разрешения
        $createLot = $auth->createPermission('createLot');
        $createLot->description = 'Добавить товар';

        $buyLot = $auth->createPermission('buyLot');
        $buyLot->description = 'Купить товар';

        $all = $auth->createPermission('all');
        $all->description = 'Все права';

        $auth->add($createLot);
        $auth->add($buyLot);
        $auth->add($all);

        $auth->addChild($seller, $createLot);
        $auth->addChild($customer, $buyLot);
        $auth->addChild($admin, $all);

        $auth->addChild($admin, $seller);
        $auth->addChild($admin, $customer);

        // создадим администратора
        $user = User::findByUsername('admin');
        if ($user) {
            $auth->assign($admin, $user->id);
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}
