<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var BlogRepository $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository('BloggerBlogBundle:Blog');

        $blogs = $repo->getLatestBlogs();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                                         ->setSubject('Contact enquiry from symblog')
                                         ->setFrom('enquiries@symblog.co.uk')
                                         ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                                         ->setBody(
                                             $this->renderView(
                                                 'BloggerBlogBundle:Page:contactEmail.txt.twig',
                                                 array('enquiry' => $enquiry)
                                             )
                                         );

                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add(
                    'blogger-notice',
                    'Your contact enquiry was successfully sent. Thank you!'
                );

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('blogger_blog_contact'));
            }
        }

        return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
