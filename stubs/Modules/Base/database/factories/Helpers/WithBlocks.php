<?php

declare(strict_types=1);

namespace Database\Factories\Helpers;

use Closure;
use Exception;
use App\Filament\Plugins\BaseBlock;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

trait WithBlocks
{
    public string $blocksColumn = 'content';

    /**
     * Add a new state transformation to the model definition.
     *
     * @param callable|array $state
     * @return Factory|static
     */
    abstract public function state($state);

    /**
     * Add a new "after creating" callback to the model definition.
     *
     * @param Closure $callback
     * @return Factory|static
     */
    abstract public function afterCreating(Closure $callback);

    /**
     * The blocks to be created for the model.
     * Accepts an integer to create that amount of random blocks, or accepts a list of blocks to be created.
     *
     * @param int|string ...$blocks
     * @return Factory
     */
    public function withBlocks(...$blocks): Factory
    {
        return $this->afterCreating(function (Model $model) use ($blocks) {
            app(BlockFactoryHandler::class)->register(
                function () use ($model, $blocks) {
                    if (count($blocks) === 1 and is_int($blocks[0])) {
                        $amount = $blocks[0];

                        $blockTypes = Config::get('blocks.active');

                        $blockTypes = collect($blockTypes)
                            ->filter(fn (string|BaseBlock $block) => ! is_null($block::factory()));

                        for ($i = 0; $i < $amount; $i++) {
                            $blocks[$i] = fake()->randomElement($blockTypes);
                        }
                    }

                    $data = [];

                    /** @var BaseBlock $block */
                    foreach ($blocks as $block) {
                        $data[] = [
                            'type' => $block::getType(),
                            'data' => $block::factory(),
                        ];
                    }

                    $model->update([
                        $this->blocksColumn => $data,
                    ]);
                }
            );
        });
    }
}
