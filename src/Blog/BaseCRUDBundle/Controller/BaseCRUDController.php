<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/24/14
 * Time: 3:17 PM
 */

namespace Blog\BaseCRUDBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Blog\BaseCRUDBundle\Entity\BaseEntity;
use Blog\BaseCRUDBundle\Util\ValidationException;


class BaseCRUDController extends Controller
{
    /**
     * Process the GET for an entity
     * Returns an Entity by id.
     *
     * @Rest\View
     *
     * @param integer $id            id param from the GET Request
     * @param string  $entityService injected string containing
     *                the entity service name
     *
     * @ApiDoc(
     *      section="BaseCRUD",
     *      description="Given an id and an EntityService,
     *          this method returns an Entity"
     * )
     *
     * @return BaseEntity
     */
    public function getAction($id, $entityService)
    {
        return $this->getEntityService($entityService)->getById($id);
    }

    /**
     * Adds an Entity
     *
     * @param Request $request       request instance from symfony
     * @param string  $entityService injected string containing
     *                the entity service name
     *
     * @Rest\View
     *
     * @ApiDoc(
     *      section="BaseEntity",
     *      statusCodes={
     *          201="Returned when successfully added",
     *          400="Returned when errors occurred"
     *      }
     * )
     *
     * @return View
     */
    public function addAction(Request $request, $entityService)
    {
        try {
            $baseEntity = $this->getEntityService($entityService)->add($request->request->all());
            return View::create(['id' => $baseEntity->getId()], Response::HTTP_CREATED);

        } catch (ValidationException $ex) {
            return View::create(['errors' => $ex->getErrors()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Edits an Entity
     *
     * @param integer $id            id param from the GET Request
     * @param Request $request       request instance from symfony
     * @param string  $entityService injected string containing
     *                the entity service name
     *
     * @Rest\View
     *
     * @ApiDoc(
     *      section="BaseEntity",
     *      statusCodes={
     *          200="Returned when successfully updated",
     *          400="Returned when errors occurred"
     *      }
     * )
     *
     * @return View
     */
    public function editAction($id, Request $request, $entityService)
    {
        $clearMissing = ($request->getMethod() == "PUT");

        try {
            $baseEntity = $this->getEntityService($entityService)
                ->update($id, $request->request->all(), $clearMissing);
            return View::create(['id' => $baseEntity->getId()], Response::HTTP_OK);

        } catch (ValidationException $ex) {
            return View::create(['errors' => $ex->getErrors()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Deletes an Entity
     *
     * @param integer $id            id param from the GET Request
     * @param string  $entityService injected string containing
     *                the entity service name
     *
     * @Rest\View
     *
     * @ApiDoc(
     *      section="ProjectCategory",
     *      statusCodes={
     *          204="Returned when successfully deleted",
     *          500="Returned when errors occurred"
     *      }
     * )
     *
     * @return View
     */
    public function deleteAction($id, $entityService)
    {
        try {
            return $this->getEntityService($entityService)->delete($id);
        } catch (ValidationException $e) {
            return View::create(['error' => $e->getErrors()], 500);
        }
    }

    public function getEntityService($entityService)
    {
        return $this->get($entityService);
    }

} 