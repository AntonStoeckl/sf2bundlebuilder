<?php

namespace Mvs\SampleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /** @var \Mvs\SampleBundle\BizModel\Product */
    protected $productBizModel;

    protected function getProductBizModel()
    {
        if ($this->productBizModel == null) {
            $this->productBizModel = $this->get('mvs_sample.productbizmodel');
        }

        return $this->productBizModel;
    }

    public function indexAction()
    {
        $products = $this->getProductBizModel()->findAll();

        return $this->render('MvsSampleBundle:Default:index.html.twig', array('products' => $products));
    }

    public function createAction()
    {
        $productArray = array(
            'name' => 'a bar foo',
            'price' => '29.95',
            'description' => 'Lorem ipsum dolores',
        );

        $product = $this->getProductBizModel()->createOne($productArray);

        return $this->redirect(
            $this->generateUrl(
                'mvs_sample_show',
                array('id' => $product->getId())
            )
        );
    }

    public function showAction($id)
    {
        $product = $this->getProductBizModel()->findOne($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return $this->render('MvsSampleBundle:Default:show.html.twig', array('product' => $product));
    }
}
