<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Xendit Payment</title>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />

</head>
<body>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        Name
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Currency
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Category
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item )
                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item['name'] }}
                    </th>
                    <td class="py-4 px-6">
                        {{ $item['currency'] }}
                    </td>
                    <td class="py-4 px-6">
                        {{ $item['channel_category'] }}
                    </td>
                    <td class="py-4 px-6">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>
</html>
