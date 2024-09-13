<?php

namespace App\Service;

use App\Entity\Calendar\Meeting;
use App\Entity\Core\User;

class MeetingService {
  /*
  * functions generic
  */
  protected $em;
  public $coreService;

  public function __construct(CoreService $coreService)
  {
      $this->coreService = $coreService;
      $this->em = $coreService->getEntityManager();
  }

  public function getWorkersBussines() : array
  {
      $business = $this->coreService->getBusiness();
      if (empty($business)) {
        return [];
      }
      $users = $this->em->getRepository(User::class)->getQueryBuilderByRoleNotLike(User::ROLE_USER)->getQuery()->getResult();
      if ($business->getBossUser()) {
        $users[] = $business->getBossUser();
      }

      foreach ($users as $key => $u) {
        if ($u->isAdministrationBusiness()) {
          unset($users[$key]);
        }
      }

      return array_values($users);
  }

  public function getAllBussinesUser() : array
  {
      $user = $this->coreService->getUser();

      if(!$user->isUserAdmin()) {
        return [];
      }
      
      $bussines = $this->em->getRepository(User::class)->getQueryBuilderByRoleLike(User::ROLE_BUSINESS)->getQuery()->getResult();
      $workers = $this->em->getRepository(User::class)->getQueryBuilderByRoleLike(User::ROLE_BUSINESS_WORKER)->getQuery()->getResult();

      $users = array_merge($workers, $bussines);

      return array_values($users);
  }

  /**
   * @param DateTime $fecha_inicio
   * @param DateTime $fecha_fin  
   * @param array $dias_de_trabajo
   * @param Meeting[] $citas
   */
  function getFreeTime(Datetime $fecha_inicio,Datetime $fecha_fin,array $dias_de_trabajo,array $citas = []) {
    // Inicializamos un array vacío para almacenar las horas libres por día
    $horas_libres_por_dia = array();

    $duration = 30;
  
    // Convertimos las fechas a objetos DateTime para facilitar el cálculo
    $fecha_inicio_obj = new DateTime($fecha_inicio->format('YYYY-MM-DD 00:00:00'));
    $fecha_fin_obj = new DateTime($fecha_fin->format('YYYY-MM-DD 23:59:59'));
  
    // Iteramos por cada día en el rango de fechas
    while ($fecha_inicio_obj <= $fecha_fin_obj) {
      // Verificamos si el día actual está dentro de los días de trabajo
      $dia_actual = strtolower($fecha_inicio_obj->format("l"));
      if (!in_array($dia_actual, array_keys($dias_de_trabajo))) {
        // Si el día actual no está dentro de los días de trabajo, avanzamos al siguiente día
        $fecha_inicio_obj->add(new DateInterval("P1D"));
        continue;
      }
  
      // Obtenemos las horas de trabajo para el día actual
      $horas_del_dia = array();
      $hora_inicio = $dias_de_trabajo[$dia_actual]["entrada"];
      $hora_fin = $dias_de_trabajo[$dia_actual]["salida"];
      $hora_actual = $hora_inicio;
  
      while ($hora_actual <= $hora_fin) {
        $horas_del_dia[$hora_actual] = null;
        $hora_actual = (new DateTime($hora_actual))->add(
            //new DateInterval("PT1H")
            new DateInterval("PT".$duration."M")
        )
        //->format("H:00");
        ->format("H:i");
      }
  
      // Iteramos por cada cita para el día actual
      foreach ($citas as $cita) {
        $cita_fecha_inicio = new DateTime($cita["fecha_inicio"]);
        $cita_fecha_fin = (new DateTime($cita["fecha_inicio"]))->add(new DateInterval("PT" . $cita["duracion"] . "M"));
  
        // Si la cita intersecta con el día actual, eliminamos las horas correspondientes del array
        if ($cita_fecha_inicio <= $fecha_inicio_obj && $cita_fecha_fin >= $fecha_inicio_obj) {
          //$hora_inicio = $cita_fecha_inicio->format("H:00");
          $hora_inicio = $cita_fecha_inicio->format("H:i");
          $hora_fin = (new DateTime($cita["fecha_inicio"]))
                    ->add(new DateInterval("PT" . $cita["duracion"] . "M"));

          if (isset($horas_del_dia[$hora_inicio]) && is_null($horas_del_dia[$hora_inicio])) {
            $horas_del_dia[$hora_inicio] = $cita;
            $diff =  $cita["duracion"] - $duration;
            if ($diff > 0) {
                do {
                    $cita_fecha_inicio->add(new DateInterval("PT" . $duration . "M"));
                    $hora_inicio = $cita_fecha_inicio->format("H:i");
                    if (isset($horas_del_dia[$hora_inicio]) && is_null($horas_del_dia[$hora_inicio])){
                        unset($horas_del_dia[$hora_inicio]);
                    }
                } while ($cita_fecha_inicio <= $cita_fecha_fin);
            }
          }
          
        }
      }
  
      // Agregamos el array de horas libres para el día actual al array principal
      $horas_libres_por_dia[$fecha_inicio_obj->format("Y-m-d")] = $horas_del_dia;
  
      // Avanzamos al siguiente día
      $fecha_inicio_obj->add(new DateInterval("P1D"));
    }
  
    // Devolvemos el array con las horas libres por día
    return $horas_libres_por_dia;
  }
      
}
