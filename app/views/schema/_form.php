<input type="hidden" id="navbar_disable_select_dairies" value="Deshabilita el select de navbar">
<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>
<div class="separator-15"></div>
<div id="id-form-schema">
<div class="row">
    <div class="col-md-12">
      <?php 
        $action = 'schema/';
        if ($schema->isPersistent()){
          $hidden = FormHelper::hidden_tag("schema[id]", $schema->id, array('id'=>'schema_id'));
          $action .= 'update';
        }
        else{
          $hidden = "";
          $action .= 'create';
        }
      ?>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form" enctype="multipart/form-data">
          <?php echo $hidden; ?>
          <div class="row">
            <div class="col-md-4">
              <fieldset>
                <legend>Tambo</legend>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <select name="schema[dairy_id]" id="dairy_id" class="form-control">
                        <?php 
                        foreach ($current_user->dairies() as $value) {
                          $selected = "";
                          if ($schema->dairy_id == $value->id)
                            $selected = " selected ";
                          echo '<option value="'.$value->id.'"'.$selected.'>'.$value->fullname().'</option>';
                        }
                        ?>
                      </select>
                      <select id="copy_from_schema" class="form-control">
                      </select>

                    </div>
                  </div>
                  <div class="col-md-12">
                    <p class="help-block">Seleccione el tambo y el esquema de control al cual se le aplicará el Control Lechero</p>
                  </div>
                </div>
                
              </fieldset>
            </div>
            <div class="col-md-5">
              <fieldset>
                <legend>Datos de Producción</legend>
                <div class="row">
                    <div class="col-md-3  col-md-offset-1">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon" id="basic-addon18">N°</span>
                          <input type="number" min="1" class="form-control" id="in_ordenio" name="schema[in_ordenio]" placeholder="Vacas en Ordeño" value="<?php echo $schema->in_ordenio;?>"  aria-describedby="basic-addon18">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-md-offset-1">
                      <div class="form-group">
                        <div class="input-group">
                          <input type="text" class="form-control" id="liters_milk" name="schema[liters_milk]" placeholder="Lts. Leche" value="<?php echo $schema->liters_milk;?>"  aria-describedby="basic-addon8">
                          <span class="input-group-addon" id="basic-addon8">Lts.</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-md-offset-1">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon" id="basic-addon9">$</span>
                          <input type="text" class="form-control" id="milk_price" name="schema[milk_price]" placeholder="$ x Lt." value="<?php echo $schema->milk_price;?>"  aria-describedby="basic-addon8">
                        </div>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                      <p class="help-block">Vacas en Ordeño, Litros diarios y precio del litro de Leche
                      </p>
                    </div>
                </div>
              </fielset>
            </div>
            <div class="col-md-2  col-md-offset-1">
              <fieldset>
                <legend>Período evaluado</legend>
                <input type="date" class="form-control" id="date" name="schema[date]" value="<?php echo $schema->date;?>">
              </fieldset>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h2 class="seccion" style="margin-left: -40px;">Esquema de control</h2>
            </div>
            <div class="col-md-12">
              <fieldset>
                <legend>Desinfección de pezones pre-ordeño</legend>
                <p>Si usted realiza desinfección pre-ordeño todos los días, indique:</p>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="control-label" for="desinf_pre_o_producto">¿Qué producto utiliza?</label>
                      <input type="text" class="form-control" id="desinf_pre_o_producto" name="schema[desinf_pre_o_producto]" value="<?php echo $schema->desinf_pre_o_producto;?>">
                    </div>
                  </div>
                  <div class="col-md-4 col-md-offset-1">
                    <div class="form-group">
                      <label class="control-label" for="desinf_pre_o_precio">¿Cuál es el precio del producto que utiliza?</label>
                      <div class="input-group col-md-4">
                        <span class="input-group-addon" id="basic-addon1">$</span>
                        <input type="text" class="form-control" id="desinf_pre_o_precio" name="schema[desinf_pre_o_precio]" value="<?php echo $schema->desinf_pre_o_precio;?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-md-offset-1">
                    <div class="form-group">
                      <label class="control-label" for="desinf_pre_o_dias">¿Cuánto le dura este producto?</label>
                      <div class="input-group col-md-6">
                        <input type="text" class="form-control" id="desinf_pre_o_dias" name="schema[desinf_pre_o_dias]" value="<?php echo $schema->desinf_pre_o_dias;?>">
                        <span class="input-group-addon" id="basic-addon1">días</span>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="col-md-12">
              <fieldset>
                <legend>Desinfección  de pezones post-ordeño</legend>
                <p>Si usted realiza desinfección post-ordeño todos los días, indique:</p>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="control-label" for="desinf_post_o_producto">¿Qué producto utiliza?</label>
                      <input type="text" class="form-control" id="desinf_post_o_producto" name="schema[desinf_post_o_producto]" value="<?php echo $schema->desinf_post_o_producto;?>">
                    </div>
                  </div>
                  <div class="col-md-4 col-md-offset-1">
                    <div class="form-group">
                      <label class="control-label" for="desinf_post_o_precio">¿Cuál es el precio del producto que utiliza?</label>
                      <div class="input-group col-md-4">
                        <span class="input-group-addon" id="basic-addon1">$</span>
                        <input type="text" class="form-control" id="desinf_post_o_precio" name="schema[desinf_post_o_precio]" value="<?php echo $schema->desinf_post_o_precio;?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-md-offset-1">
                    <div class="form-group">
                      <label class="control-label" for="desinf_post_o_dias">¿Cuánto le dura este producto?</label>
                      <div class="input-group col-md-6">
                        <input type="text" class="form-control" id="desinf_post_o_dias" name="schema[desinf_post_o_dias]" value="<?php echo $schema->desinf_post_o_dias;?>">
                        <span class="input-group-addon" id="basic-addon1">días</span>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <!-- INICIO TRATAMIENTO MC -->
            <div class="col-md-12">
              <fieldset>
                <legend>Tratamiento de Mastitis Clínica</legend>
                <div class="row">
                  <div class="col-md-12">
                    <p>Si usted utiliza pomo intramamario, indique:</p>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label col-sm-8" for="tmc_ab_pomo_precio">¿Precio de cada pomo?</label>
                      <div class="input-group col-sm-4">
                        <span class="input-group-addon" id="basic-addon3">$</span>
                        <input type="text" class="form-control" id="tmc_ab_pomo_precio" name="schema[tmc_ab_pomo_precio]" value="<?php echo $schema->tmc_ab_pomo_precio;?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="control-label  col-sm-10" for="tmc_ab_pomo_cantidad">¿Cantidad de pomos utilizados para tratar un caso de mastitis clínica?</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control" id="tmc_ab_pomo_cantidad" name="schema[tmc_ab_pomo_cantidad]" value="<?php echo $schema->tmc_ab_pomo_cantidad;?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <p>Si usted utiliza antibiótico inyectable, indique:</p>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label col-sm-8" for="tmc_ab_inyect_precio">¿Precio de la dosis/vaca?</label>
                      <div class="input-group col-sm-4">
                        <span class="input-group-addon" id="basic-addon3">$</span>
                        <input type="text" class="form-control" id="tmc_ab_inyect_precio" name="schema[tmc_ab_inyect_precio]" value="<?php echo $schema->tmc_ab_inyect_precio;?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="control-label  col-sm-10" for="tmc_ab_inyect_cantidad">¿Número de dosis utilizadas para tratar un caso de mastitis clínica?</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control" id="tmc_ab_inyect_cantidad" name="schema[tmc_ab_inyect_cantidad]" value="<?php echo $schema->tmc_ab_inyect_cantidad;?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <p>Si usted utiliza anti-inflamatorio inyectable, indique:</p>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label col-sm-8" for="tmc_ai_inyect_precio">¿Precio de la dosis/vaca?</label>
                      <div class="input-group col-sm-4">
                        <span class="input-group-addon" id="basic-addon3">$</span>
                        <input type="text" class="form-control" id="tmc_ai_inyect_precio" name="schema[tmc_ai_inyect_precio]" value="<?php echo $schema->tmc_ai_inyect_precio;?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="control-label  col-sm-10" for="tmc_ai_inyect_cantidad">¿Número de dosis utilizadas para tratar un caso de mastitis clínica?</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control" id="tmc_ai_inyect_cantidad" name="schema[tmc_ai_inyect_cantidad]" value="<?php echo $schema->tmc_ai_inyect_cantidad;?>">
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <!-- FIN TRATAMIENTO MC -->
            <!-- INICIO TRATAMIENTO SECADO -->
            <div class="col-md-12">
              <fieldset>
                <legend>Tratamiento al Secado</legend>
                <div class="row">
                  <div class="col-md-12">
                    <p>Si usted trata a todas las vacas al momento del secado, indique:</p>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label col-sm-8" for="ts_pomo_precio">¿Precio de cada pomo?</label>
                      <div class="input-group col-sm-4">
                        <span class="input-group-addon" id="basic-addon1">$</span>
                        <input type="text" class="form-control" id="ts_pomo_precio" name="schema[ts_pomo_precio]" value="<?php echo $schema->ts_pomo_precio;?>">
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <!-- FIN TRATAMIENTO SECADO -->
            <!-- INICIO MANTENIMIENTO -->
            <div class="col-md-12">
              <fieldset>
                <legend>Chequeo Máquina de Ordeño</legend>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label" for="maquina_control_precio">¿Cuánto le cuesta un chequeo de la máquina de ordeño?</label>
                      <div class="input-group col-sm-4">
                        <span class="input-group-addon" id="basic-addon3">$</span>
                        <input type="text" class="form-control" id="maquina_control_precio" name="schema[maquina_control_precio]" value="<?php echo $schema->maquina_control_precio;?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5 col-md-offset-1">
                    <div class="form-group">
                      <label class="control-label" for="maquina_control_dias">¿Cada cuánto realiza el chequeo de la máquina de ordeño?</label>
                      <div class="input-group  col-sm-4">
                        <input type="text" class="form-control" id="maquina_control_dias" name="schema[maquina_control_dias]" value="<?php echo $schema->maquina_control_dias;?>">
                        <span class="input-group-addon" id="basic-addon13">dias</span>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <!-- FIN MANTENIMIENTO -->
            <!-- INICIO OTRAS EROGACIONES -->
            <div class="col-md-12">
              <?php include "_form_erogaciones.php"; ?>
            </div>
            <!-- FIN OTRAS EROGACIONES -->
          </div>
          <div class="row">
            <div class="col-md-12">
              <h2 class="seccion" style="margin-left: -40px;">Control Lechero</h2>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                    <p class="help-block">Archivo de Excel o CSV separado por coma. Debe contener como mínimo las siguientes columnas</p>
                    <table class="table">
                      <tr>
                        <th>numero</th>
                        <th>rcs</th>
                        <th>nop</th>
                        <th>mc</th>
                        <th>del o fecha_parto</th>
                      </tr>
                      <tr>
                        <td>Número de Caravana</td>
                        <td>Recuento de células somáticas</td>
                        <td>Número ordinal de parto</td>
                        <td>0 si tiene Mastitis Clínia sino 1</td>
                        <td>Días de lactancia o fecha última lactancia</td>
                      </tr>
                    </table>
                    <p class="help-block">Opcionales. 
                      <ul>
                        <li><strong>litros</strong>: producidos por la vaca</li>
                        <li>
                          <strong>baja y fecha_baja</strong>: Motivo baja y fecha, los motivos pueden ser
                          <ul>
                            <li><?php echo DairyControl::BAJA_SECADO ?></li>
                            <li><?php echo DairyControl::BAJA_MUERTE ?></li>
                            <li><?php echo DairyControl::BAJA_DESCARTE ?></li>
                            <li><?php echo DairyControl::BAJA_OTRO ?>: otros tratamientos (por otras enfermedades)</li>
                          </ul>
                        </li>
                      </ul>
                    </p>
              </div>
            </div>
            <div class="col-md-5 col-md-offset-1">
              <div class="form-group">
                <label class="control-label col-sm-2" for="file_data">Importar</label>
                <span class="help-block">Seleccione el archivo donde tiene toda la información provista por el Control Lechero</span>
                <div class="col-sm-10">
                    <input type="file" id="file_data" name="file_data" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group text-center"> 
                <button type="submit" name="boton" class="btn btn-success">Aceptar</button>
            </div>
          </div>
        </form>
    </div>
</div>
</div>