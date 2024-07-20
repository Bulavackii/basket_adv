<?php
declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_UPDATE_NAME = 4;
const OPERATION_UPDATE_QUANTITY = 5;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_UPDATE_NAME => OPERATION_UPDATE_NAME . '. Изменить название товара.',
    OPERATION_UPDATE_QUANTITY => OPERATION_UPDATE_QUANTITY . '. Изменить количество товара.',
];

$items = [];

function clearScreen(): void {
    system('clear'); // Для Unix-подобных систем
    // system('cls'); // Для Windows, если используется
}

function displayMenu(array $operations): int {
    global $items;

    if (count($items)) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        foreach ($items as $item) {
            echo $item['name'] . ' - ' . $item['quantity'] . PHP_EOL;
        }
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }

    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
    $operationNumber = (int) trim(fgets(STDIN));

    return $operationNumber;
}

function addItem(array &$items): void {
    echo "Введите название товара для добавления в список: \n> ";
    $itemName = trim(fgets(STDIN));
    echo "Введите количество товара: \n> ";
    $itemQuantity = (int) trim(fgets(STDIN));

    $items[] = [
        'name' => $itemName,
        'quantity' => $itemQuantity
    ];
}

function deleteItem(array &$items): void {
    if (empty($items)) {
        echo 'Список покупок пуст. Нет товаров для удаления.' . PHP_EOL;
        return;
    }

    echo 'Текущий список покупок:' . PHP_EOL;
    foreach ($items as $item) {
        echo $item['name'] . ' - ' . $item['quantity'] . PHP_EOL;
    }
    echo 'Введите название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    $items = array_filter($items, function($item) use ($itemName) {
        return $item['name'] !== $itemName;
    });

    if (count($items) === count($items)) {
        echo 'Товар не найден в списке.' . PHP_EOL;
    }
}

function updateItemName(array &$items): void {
    if (empty($items)) {
        echo 'Список покупок пуст. Нет товаров для изменения.' . PHP_EOL;
        return;
    }

    echo 'Текущий список покупок:' . PHP_EOL;
    foreach ($items as $item) {
        echo $item['name'] . ' - ' . $item['quantity'] . PHP_EOL;
    }
    echo 'Введите старое название товара:' . PHP_EOL . '> ';
    $oldName = trim(fgets(STDIN));
    echo 'Введите новое название товара:' . PHP_EOL . '> ';
    $newName = trim(fgets(STDIN));

    foreach ($items as &$item) {
        if ($item['name'] === $oldName) {
            $item['name'] = $newName;
        }
    }
}

function updateItemQuantity(array &$items): void {
    if (empty($items)) {
        echo 'Список покупок пуст. Нет товаров для изменения.' . PHP_EOL;
        return;
    }

    echo 'Текущий список покупок:' . PHP_EOL;
    foreach ($items as $item) {
        echo $item['name'] . ' - ' . $item['quantity'] . PHP_EOL;
    }
    echo 'Введите название товара для изменения количества:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));
    echo 'Введите новое количество товара:' . PHP_EOL . '> ';
    $newQuantity = (int) trim(fgets(STDIN));

    foreach ($items as &$item) {
        if ($item['name'] === $itemName) {
            $item['quantity'] = $newQuantity;
        }
    }
}

function printItems(array $items): void {
    if (count($items)) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        foreach ($items as $item) {
            echo $item['name'] . ' - ' . $item['quantity'] . PHP_EOL;
        }
        echo 'Всего ' . count($items) . ' позиций. ' . PHP_EOL;
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
    echo 'Нажмите Enter для продолжения';
    fgets(STDIN);
}

do {
    clearScreen();

    do {
        $operationNumber = displayMenu($operations);
        if (!array_key_exists($operationNumber, $operations)) {
            clearScreen();
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }
    } while (!array_key_exists($operationNumber, $operations));

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem($items);
            break;

        case OPERATION_DELETE:
            deleteItem($items);
            break;

        case OPERATION_PRINT:
            printItems($items);
            break;

        case OPERATION_UPDATE_NAME:
            updateItemName($items);
            break;

        case OPERATION_UPDATE_QUANTITY:
            updateItemQuantity($items);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
?>;
