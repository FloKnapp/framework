<?php

namespace Webasics\Framework\View;

use Webasics\Framework\Filesystem\Exception\FileNotFoundException;
use Webasics\Framework\View\Exception\TemplateException;
use Webasics\Framework\View\Exception\ViewHelperException;
use Webasics\Framework\View\Helper\Block;
use Webasics\Framework\View\Helper\ParentTemplate;
use Webasics\Framework\View\Helper\RenderBlock;

/**
 * Class AbstractView
 * @package Webasics\Framework\View
 */
abstract class AbstractView implements ViewInterface
{

    /**
     * Holds the view template
     * @var string
     */
    private string $template = '';

    /**
     * Holds the view variables
     * @var array
     */
    private array $variables = [];

    /**
     * Holds the parent template
     * @var AbstractView|null
     */
    private ?AbstractView $parentView = null;

    /**
     * AbstractView constructor.
     * @param string $template
     */
    public function __construct(string $template = '')
    {
        $this->template = $template;
    }

    /**
     * Set template for this view
     *
     * @param string $template
     * @return self
     *
     * @throws FileNotFoundException
     */
    public function setTemplate(string $template = ''): self
    {
        if (empty($template) || !file_exists($template) || is_dir($template)) {
            throw new FileNotFoundException('Template "' . $template . '" not found');
        }

        $this->template = $template;

        return $this;
    }

    /**
     * Add javascript from outside
     *
     * @param string $file
     * @return self
     */
    public function addScript(string $file): self
    {
        $this->variables['assetsJs'][] = $file;
        return $this;
    }

    /**
     * Add stylesheet from outside
     *
     * @param string $file
     * @return self
     */
    public function addStylesheet(string $file): self
    {
        $this->variables['assetsCss'][] = $file;
        return $this;
    }

    /**
     * Return current template
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set a single variable
     *
     * @param string $key
     * @param string|array $value
     */
    public function setVariable(string $key = '', $value = ''): void
    {
        $this->variables[$key] = $value;
    }

    /**
     * Get a single variable
     *
     * @param string $key
     * @return string|array
     */
    public function getVariable(string $key)
    {
        return $this->variables[$key] ?? '';
    }

    /**
     * Check if variable exists
     *
     * @param string $key
     * @return bool
     */
    public function hasVariable(string $key): bool
    {
        if(isset($this->variables[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Set many variables at once
     *
     * @param array $variables
     * @return self
     */
    public function setVariables(array $variables = []): self
    {
        foreach($variables AS $key=>$value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * Get all variables
     *
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Define parent template
     *
     * @param AbstractView $view
     */
    public function setParentView(AbstractView $view): void
    {
        $this->parentView = $view;
    }

    /**
     * Get parent template
     *
     * @return AbstractView
     */
    public function getParentView():? AbstractView
    {
        return $this->parentView;
    }

    /**
     * Strip spaces and tabs from output
     *
     * @param $output
     * @return string
     */
    private function normalizeOutput($output): string
    {
        if (getenv('APPLICATION_ENV') === 'production') {
            return preg_replace('/(\s{2,}|\t|\r|\n)/', ' ', trim($output));
        }

        // Dev environment
        return str_replace(["\t", "\r", "\n\n\n"], ' ', trim($output));
    }

    /**
     * Render the current view
     *
     * @return string
     *
     * @throws TemplateException
     * @throws FileNotFoundException
     * @throws ViewHelperException
     */
    public function render(): string
    {
        try {

            extract($this->variables, EXTR_OVERWRITE);

            ob_start();

            include $this->getTemplate();

            $content = ob_get_contents();

        } catch (\Exception $e) {
            ob_end_clean();
            throw new TemplateException($e->getMessage(), 0, $e);
        }

        if (ob_get_length() >= 0) {
            ob_end_clean();
        }

        if (!empty($content)) {
            $content = $this->parseTemplate($content);
        }

        if ($this->getParentView() instanceof AbstractView) {
            return $this->normalizeOutput($this->getParentView()->setVariables($this->getVariables())->render());
        }

        return $this->normalizeOutput($content);

    }

    /**
     * @param string $content
     * @return string
     * @throws ViewHelperException
     */
    private function parseTemplate($content = '')
    {
        try {

            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);

            $dom->loadHTML($content);

            $helpers = $this->formatHelpers($dom);
            $result = $dom->saveHTML();

            foreach ($helpers as $tag => $helperList) {

                foreach ($helperList as $data) {

                    /** @var AbstractViewHelper $helperObject */
                    $helperObject = new $data['class'];
                    $helperObject->setView($this);

                    if (!method_exists($helperObject, 'translate')) {
                        continue 2;
                    }

                    $helperArguments = $helperObject::translate($data);
                    $helperResult = $this->_callUserFuncArray($helperObject, $helperArguments);

                    if (!empty($helperResult)) {

                        $attributes = array_keys($data['attributes'] ?? []);
                        $attributes = ' ' . implode(' ', $attributes);

                        $htmlTag = '<' . $tag . $attributes . '></' . $tag . '>';

                        $result = str_replace($htmlTag, $helperResult, $result);

                    }

                }

            }

        } catch (\Throwable $e) {
            throw new ViewHelperException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param \DOMDocument $dom
     * @return array
     */
    private function formatHelpers(\DOMDocument $dom)
    {
        $viewHelperMapping = [
            'extends' => ParentTemplate::class,
            'block'   => Block::class,
            'render'  => RenderBlock::class
        ];

        $result = [];

        foreach ($viewHelperMapping as $tag => $helper) {

            /** @var \DOMNodeList $elements */
            $elements = $dom->getElementsByTagName($tag);

            /** @var \DOMElement|array $element */
            foreach ($elements as $element) {

                $attributes = [];

                if ($element->attributes && $element->attributes->length > 0) {

                    foreach ($element->attributes as $attribute) {
                        $attributes[$attribute->name] = $attribute->value;
                    }

                }

                $result[$tag][] = [
                    'class'      => $helper,
                    'attributes' => $attributes,
                    'value'      => $element->nodeValue,
                    'element'    => $element
                ];

            }

        }

        return $result;

    }

    /**
     * Magic method for providing a view helpers
     *
     * @param  string $name      The class name
     * @param  array  $arguments Arguments if given
     *
     * @return AbstractViewHelper
     *
     * @throws ViewHelperException
     */
    public function __call($name, $arguments)
    {
        $config = Container::load()->get(Configuration::class);
        $namespace = $config->get('app:namespace');

        $coreViewHelper   = __NAMESPACE__ . '\View\Helper\\' . ucfirst($name);
        $customViewHelper = $namespace . '\View\Helper\\' . ucfirst($name);

        // Search in root view helpers

        if (class_exists($coreViewHelper)) {

            /** @var AbstractViewHelper $class */
            $class = new $coreViewHelper;
            $class->setView($this);

            return $this->_callUserFuncArray($class, $arguments);

        }

        // Search in custom view helpers

        if (class_exists($customViewHelper)) {

            /** @var AbstractViewHelper $class */
            $class = new $customViewHelper;
            $class->setView($this);

            return $this->_callUserFuncArray($class, $arguments);

        }

        throw new ViewHelperException('No view helper for "' . $name . '" found.');
    }

    /**
     * Abstraction of call_user_func_array
     *
     * @param $class
     * @param $arguments
     *
     * @return mixed
     */
    private function _callUserFuncArray($class, $arguments)
    {
        return call_user_func_array($class, $arguments);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->variables, $this->template);
    }

}