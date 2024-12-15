<?php

declare(strict_types=1);

namespace Database\Factories\Helpers;

use SplFileInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Awcodes\Curator\Models\Media;
use Intervention\Image\AbstractFont;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\Facades\Image as ImageFacade;

class FactoryImage extends FileFactory
{
    use WithFaker;

    protected ?string $label = null;

    public bool $labelOnDummyImage = false;

    public bool $colorizeDummyImage = false;

    protected array $defaultImages = [];

    protected array $dummyImages = [];

    protected ?array $crop = null;

    protected int $width = 400;

    protected int $height = 300;

    public function __construct()
    {
        $this->setUpFaker();

        $this->setupDummyImages();
    }

    /**
     * Make the Factory Image.
     *
     * @param bool|string|null $label
     * @return static
     */
    public static function make($label = null): self
    {
        $instance = new static();

        if ($label) {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * Fill the dummy images array with default image paths.
     */
    protected function setupDummyImages(): void
    {
        $files = [];

        $path = database_path('factories/DummyImages');

        if (FileFacade::exists($path)) {
            $files = FileFacade::allFiles($path);
        }

        $this->dummyImages = $this->defaultImages = collect($files)->map(fn (SplFileInfo $file) => $file->getRealPath())->toArray();
    }

    /**
     * Set a label for the generated image.
     * If set to true, it will generate a random label.
     *
     * @param bool|string $label
     * @return static
     */
    public function label($label = true): self
    {
        if ($label === true) {
            $label = $this->faker->word;
        }

        $this->label = $label;

        return $this;
    }

    /**
     * Set the width of the generated image when not making use of dummy images.
     *
     * @param int $width
     * @return $this
     */
    public function width(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the height of the generated image when not making use of dummy images.
     *
     * @param int $height
     * @return $this
     */
    public function height(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Replace the default dummy images with custom dummy images.
     * This requires you to give the full path to the file.
     *
     * @param array $images
     * @param bool $override
     * @return static
     */
    public function dummyImages(array $images, bool $override = false): self
    {
        if ($override) {
            $this->dummyImages = [];
        }

        $this->dummyImages = $images;

        return $this;
    }

    /**
     * Enable or disable adding a color overlay over dummy images.
     *
     * @param bool $colorizeDummyImage
     * @return $this
     */
    public function colorizeDummyImage(bool $colorizeDummyImage = true): self
    {
        $this->colorizeDummyImage = $colorizeDummyImage;

        return $this;
    }

    /**
     * Enable or disable showing label on dummy images.
     *
     * @param bool $labelOnDummyImage
     * @return $this
     */
    public function labelOnDummyImage(bool $labelOnDummyImage = true): self
    {
        $this->labelOnDummyImage = $labelOnDummyImage;

        return $this;
    }

    /**
     * Create a factory image for an image field.
     *
     * @param string $disk
     * @return string
     */
    public function imageField(string $disk = 'public'): string
    {
        $file = $this->createImage();
        $name = Str::random(32) . '.' . $file->extension;

        Storage::disk($disk)->put($name, $file->encode());

        return $name;
    }

    /**
     * Create a factory image for a filemanager field.
     *
     * @return string
     */
    public function fileManagerField(): int
    {
        $fileModel = $this->createFilemanagerImage();

        return $fileModel->id;
    }

    /**
     * Create a factory image for a cropper field.
     *
     * @param int $width
     * @param int $height
     * @return string
     */
    public function cropperField(int $width, int $height): int
    {
        $fileModel = $this->createFilemanagerImage();

        return $fileModel->id;
    }

    /**
     * Create factory images for a gallery field.
     *
     * @param int $amount
     * @return string
     */
    public function galleryField(int $amount = 1): int
    {
        $array = [];

        $label = $this->label;

        for ($i = 0; $i < $amount; $i++) {
            if ($label && $amount > 1) {
                $this->label($label . ' ' . ($i + 1));
            }

            $array[] = [
                'image' => $this->fileManagerField(),
                'title' => $this->label,
                'alt' => $this->label,
            ];
        }

        return json_encode($array);
    }

    protected function createFileManagerThumbnail(Image $file, string $disk, string $filename): void
    {
        $path = sprintf(
            '%s/%s/%s',
            Config::get('lfm.folder_categories.file.folder_name'),
            Config::get('lfm.shared_folder_name'),
            Config::get('lfm.thumb_folder_name'),
        );

        $file->fit(200, 200);
        $file->crop(200, 200);

        $fullPath = $path . DIRECTORY_SEPARATOR . $filename;

        Storage::disk($disk)->put($fullPath, $file->encode());
    }

    /**
     * Create the temporary image.
     *
     * @return Image
     */
    protected function createImage(): Image
    {
        /** @var Image $image */
        $image = null;

        $showLabel = true;

        if (! empty($this->dummyImages)) {
            $image = ImageFacade::make(Arr::random($this->dummyImages));

            if ($this->colorizeDummyImage) {
                $image->colorize(
                    $this->faker->numberBetween(-80, 50),
                    $this->faker->numberBetween(-80, 50),
                    $this->faker->numberBetween(-80, 50),
                );
            }

            $showLabel = $this->labelOnDummyImage;
        } else {
            $image = ImageFacade::make($this->image(Str::random() . '.jpg', $this->width, $this->height));
            $image->extension = 'jpg';

            $image->colorize(
                $this->faker->numberBetween(0, 100),
                $this->faker->numberBetween(0, 100),
                $this->faker->numberBetween(0, 100),
            );
        }

        if ($this->label && $showLabel) {
            $image->text(
                $this->label,
                $image->width() / 2,
                $image->height() / 2,
                function (AbstractFont $font) use ($image) {
                    $font->size(min($image->height(), $image->width()) / 10);
                    $font->color('#ffffff');
                    $font->align('center');
                    $font->valign('center');
                    $font->angle($this->faker->numberBetween(-40, 40));
                }
            );
        }

        $this->dummyImages = $this->defaultImages;

        return $image;
    }

    /**
     * Create a filemanager file from Intervention Image and save it to the disk.
     */
    protected function createFilemanagerImage(): Media
    {
        $file = $this->createImage();
        $basename = uuid_create();
        $filename = $basename . '.' . $file->extension;
        $path = 'media/' . $filename;

        Storage::disk('public')->put($path, $file->encode());

        /** @var Media|Model $model */
        $model = Media::query()
            ->create([
                'name' => $basename,
                'title' => $file->basename,
                'path' => $path,
                'ext' => $file->extension,
                'type' => $file->mime(),
                'width' => $file->width(),
                'height' => $file->height(),
                'size' => $file->filesize(),
            ]);

        return $model;
    }
}
