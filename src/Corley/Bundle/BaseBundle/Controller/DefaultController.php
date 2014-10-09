<?php

namespace Corley\Bundle\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Corley\Bundle\BaseBundle\Entity\User;
use Corley\Bundle\BaseBundle\Form\UserType;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        if ($name == "fabien") {
            $name = "Captain on the bridge";
        }

        return $this->render('CorleyBaseBundle:Default:index.html.twig', array('name' => $name));
    }

    public function signupAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getManager();

            $existingUser = $em->getRepository("CorleyBaseBundle:User")->findOneByEmail($form->get("email")->getData());

            if ($existingUser) {
                throw new \Exception("User already present");
            }

            $entity->setConfirmed(false);

            $em->persist($entity);
            $em->flush();

             $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom("company@domain.tld")
                ->setTo($entity->getEmail())
                ->setBody(
                    $this->renderView(
                        'CorleyBaseBundle:Default:index.html.twig',
                        array('name' => $entity->getName())
                    )
                )
            ;
            $this->get('mailer')->send($message);

            return $this->redirect($this->generateUrl('corley_base_homepage', array("name" => "reserved area")));
        }

        return $this->render('CorleyBaseBundle:Default:signup.html.twig', array(
            "form" => $form->createView()
        ));
    }

    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('corley_base_signup'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}
