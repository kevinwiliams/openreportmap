<?php

return [
    'disk_name' => env('MEDIA_DISK', 'uploads'),

    'max_file_size' => 1024 * 1024 * 10,

    'queue_connection_name' => env('MEDIA_QUEUE_CONNECTION'),

    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    'media_collection_models' => [],

    'path_generator' => Spatie\MediaLibrary\PathGenerator\DefaultPathGenerator::class,

    'file_namer' => Spatie\MediaLibrary\FileNamer\DefaultFileNamer::class,

    'image_generator' => Spatie\MediaLibrary\ImageGenerators\ImageGeneratorFactory::class,

    'manipulation_sequence' => Spatie\Image\Manipulations::class,

    'remote' => [
        'headers' => [],
    ],

    'responsive_images' => [
        'use_tiny_placeholders' => true,
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],

    'default_loading_attribute_value' => null,

    'media_library' => [
        'image_optimizers' => [
            Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
                '--strip-all',
                '--all-progressive',
            ],
            Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
                '--force',
            ],
            Spatie\ImageOptimizer\Optimizers\Optipng::class => [
                '-i0',
                '-o2',
                '-quiet',
            ],
            Spatie\ImageOptimizer\Optimizers\Svgo::class => [
                '--disable=cleanupIDs',
            ],
            Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
                '-b',
                '-O3',
            ],
            Spatie\ImageOptimizer\Optimizers\Cwebp::class => [
                '-m 6',
                '-pass 10',
                '-mt',
                '-q 90',
            ],
        ],
    ],
];
