<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\Core\Events\CollectingAssets;
use Modules\Core\Events\EditorIsRendering;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;
use Modules\Core\Foundation\Asset\Types\AssetTypeFactory;

class AssetsViewComposer
{
    /**
     * @var AssetManager
     */
    protected $assetManager;
    /**
     * @var AssetPipeline
     */
    protected $assetPipeline;
    /**
     * @var AssetTypeFactory
     */
    protected $assetFactory;
    /**
     * @var Request
     */
    private $request;

    public function __construct(AssetManager $assetManager, AssetPipeline $assetPipeline, AssetTypeFactory $assetTypeFactory, Request $request)
    {
        $this->assetManager = $assetManager;
        $this->assetPipeline = $assetPipeline;
        $this->assetFactory = $assetTypeFactory;
        $this->request = $request;
    }

    public function compose(View $view)
    {
        if (app('asgard.onBackend') === false) {
            return;
        }

        foreach (config('asgard.core.core.admin-assets') as $assetName => $path) {
            $path = $this->assetFactory->make($path)->url();
            $this->assetManager->addAsset($assetName, $path);
        }
        $this->assetPipeline->requireCss(config('asgard.core.core.admin-required-assets.css'));
        $this->assetPipeline->requireJs(config('asgard.core.core.admin-required-assets.js'));

        event($editor = new EditorIsRendering($this->assetPipeline));
        event(new CollectingAssets($this->assetPipeline));

        $view->with('cssFiles', $this->assetPipeline->allCss());
        $view->with('jsFiles', $this->assetPipeline->allJs());
        $view->with('editor', $editor);
    }
}
