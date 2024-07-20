<?php
// Включаем строгий режим типизации
declare(strict_types=1);

// Определение констант для операций
const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

// Массив с описаниями операций
$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

// Инициализация массива для хранения списка покупок
$items = [];

/**
 * Функция для очистки экрана.
 * На Linux используется команда 'clear', на Windows - 'cls'.
 */
function clearScreen(): void {
    system('clear'); // Для Linux и macOS
    // system('cls'); // Для Windows, строка закомментирована
}

/**
 * Функция для отображения меню и запроса операции от пользователя.
 *
 * @param array $operations Массив с описаниями операций.
 * @return int Номер выбранной операции.
 */
function displayMenu(array $operations): int {
    global $items; // Использование глобальной переменной $items

    if (count($items)) {
        // Если список покупок не пуст, отображаем его
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode("\n", $items) . "\n";
    } else {
        // Если список покупок пуст, сообщаем об этом
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }

    // Выводим меню операций
    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
    
    // Читаем ввод пользователя и приводим его к целому числу
    $operationNumber = (int) trim(fgets(STDIN));

    return $operationNumber; // Возвращаем номер выбранной операции
}

/**
 * Функция для добавления товара в список покупок.
 *
 * @param array &$items Ссылка на массив с товарами.
 */
function addItem(array &$items): void {
    echo "Введите название товара для добавления в список: \n> ";
    
    // Читаем название товара от пользователя
    $itemName = trim(fgets(STDIN));
    
    // Добавляем товар в массив
    $items[] = $itemName;
}

/**
 * Функция для удаления товара из списка покупок.
 *
 * @param array &$items Ссылка на массив с товарами.
 */
function deleteItem(array &$items): void {
    if (empty($items)) {
        // Если список пуст, сообщаем об этом и возвращаемся
        echo 'Список покупок пуст. Нет товаров для удаления.' . PHP_EOL;
        return;
    }

    // Отображаем текущий список покупок
    echo 'Текущий список покупок:' . PHP_EOL;
    echo implode("\n", $items) . "\n";
    
    echo 'Введите название товара для удаления из списка:' . PHP_EOL . '> ';
    
    // Читаем название товара от пользователя
    $itemName = trim(fgets(STDIN));

    if (in_array($itemName, $items, true)) {
        // Если товар найден в списке, удаляем его
        while (($key = array_search($itemName, $items, true)) !== false) {
            unset($items[$key]);
        }
        // Пересобираем массив с индексами от 0
        $items = array_values($items);
    } else {
        // Если товар не найден, сообщаем об этом
        echo 'Товар не найден в списке.' . PHP_EOL;
    }
}

/**
 * Функция для отображения списка покупок.
 *
 * @param array $items Массив с товарами.
 */
function printItems(array $items): void {
    if (count($items)) {
        // Если список не пуст, отображаем его
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode(PHP_EOL, $items) . PHP_EOL;
        echo 'Всего ' . count($items) . ' позиций. ' . PHP_EOL;
    } else {
        // Если список пуст, сообщаем об этом
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
    // Просим пользователя нажать Enter для продолжения
    echo 'Нажмите Enter для продолжения';
    fgets(STDIN);
}

// Основной цикл программы
do {
    clearScreen(); // Очищаем экран

    // Внутренний цикл для выбора корректной операции
    do {
        $operationNumber = displayMenu($operations); // Отображаем меню и получаем номер операции
        if (!array_key_exists($operationNumber, $operations)) {
            clearScreen(); // Очищаем экран
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }
    } while (!array_key_exists($operationNumber, $operations));

    // Отображаем выбранную операцию
    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    // Выполняем действие в зависимости от выбранной операции
    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem($items); // Добавление товара
            break;

        case OPERATION_DELETE:
            deleteItem($items); // Удаление товара
            break;

        case OPERATION_PRINT:
            printItems($items); // Отображение списка покупок
            break;
    }

    echo "\n ----- \n"; // Разделитель между операциями
} while ($operationNumber > 0); // Условие завершения программы

// Сообщение о завершении программы
echo 'Программа завершена' . PHP_EOL;
?>;
