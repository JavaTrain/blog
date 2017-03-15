<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * @param $id
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id ,$slug)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $em->getRepository('BloggerBlogBundle:Comment')
                       ->getCommentsForBlog($blog->getId());

        return $this->render(
            'BloggerBlogBundle:Blog:show.html.twig',
            array(
                'blog'     => $blog,
                'comments' => $comments
            )
        );
    }
}