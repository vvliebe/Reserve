<?php

    /**
     * Created by PhpStorm.
     * User: Norgerman
     * Date: 12/21/14
     * Time: 1:53 PM
     */
    class OrderController
        extends BaseController
    {
        public function getIndex()
        {
            $result = array();
            $id = Input::get("id");
            if (!Input::has("order_id"))
            {
                App::abort(404);
            }
            $order_id = Input::get("order_id");
            $order = Order::find($order_id);
            if ($order->owner_id != $id)
            {
                //TODO:Vaildate
            }
            $hospital = Hospital::find($order->hospital_id);
            $result["hos_name"] = $hospital->name;
            $result["dep_name"] = Department::find($order->department_id)->name;
            $result["date"] = $order->date;
            $result["time"] = $order->time;
            $result["o_id"] = $order_id;
            $result["price"] = $hospital->price;
            $result["doc_name"] = $order->visit->doctor->name;
        }

        public function getUserinfo()
        {
            if (Session::has("id"))
            {
                $id = Session::get("id");
                $user = Registeruser::select(array("name", "idnum", "tel"))
                                    ->find($id)
                                    ->toArray();

                return Response::json($user);
            }
            else
            {
                App::abort(403, "Unauthorized");
            }
        }

        public function getPrint()
        {
            if (Session::has("id"))
            {
                $id = Session::get("id");
                $user = Registeruser::find($id);
                $order_id = Input::get("order_id");
                $ord = Order::find($order_id);
                $visit = $ord->visit;
                $docname = $visit->doctor->name;
                $hospital = Hospital::find($ord->hospital_id);
                $hosname = $hospital->name;
                $price = $hospital->price;
                $depname = Department::find($ord->department_id)->name;
                $order = array("o_id" => $ord->o_id,
                               "price" => $price,
                               "doctorname" => $docname,
                               "hospitalname" => $hosname,
                               "departmentname" => $depname,
                               "date" => $visit->work_date,
                               "time" => $ord->time,
                               "status" => $ord->status);

                return View::make("personinfo.print", array("userinfo" => $user->toArray(),
                                                            "orderinfo" => $order));
            }
            else
            {
                App::abort(403, "Unauthorized");
            }
        }

        public function postOrder()
        {
            if (Session::has("id"))
            {
                if (Input::has("visit_id"))
                {
                    $visit_id = Input::get("visit_id");
                    $time = Input::get("time");
                    $department_id = Session::get("dep_id");
                    $hospital_id = Session::get("hos_id");
                    $id = Session::get("id");
                    try
                    {
                        DB::begintransaction();
                        $visit = Visit::find($visit_id);
                        if ($visit == null)
                        {
                            DB::rollback();
                            App::abort(404, "visit not found");
                        }
                        if ($time == 1)
                        {
                            if ($visit->am < 1)
                            {
                                throw new PDOException("none", -1);
                            }
                            $visit->am--;
                        }
                        else if ($time == 2)
                        {
                            if ($visit->pm < 1)
                            {
                                throw new PDOException("none", -1);
                            }
                            $visit->pm--;
                        }
                        else if ($time == 3)
                        {
                            if ($visit->ng < 1)
                            {
                                throw new PDOException("none", -1);
                            }
                            $visit->ng--;
                        }
                        else
                        {
                            throw new PDOException("unknow", -3);
                        }
                        if (!$visit->save())
                        {
                            throw new PDOException("unknow", -3);
                        }
                        $order = new Order();
                        $order->owner_id = $id;
                        $order->visit_id = $visit_id;
                        $order->department_id = $department_id;
                        $order->hospital_id = $hospital_id;
                        $order->time = $time;
                        if (!$order->save())
                        {
                            throw new PDOException(-3);
                        }
                        DB::commit();

                        return Response::json(array("status" => 1, "o_id" => $order->o_id));
                    }
                    catch (PDOException $e)
                    {
                        DB::rollback();

                        return Response::json(array("status" => $e->getCode(), "error" => $e->getMessage()));
                    }

                }
                else
                {
                    return Response::json(array("status" => -3, "error" => "unknow"));
                }

            }
            else
            {
                App::abort(403, "Unauthorized");
            }
        }

        public function postPay()
        {
            $order_id = Input::get("order_id");
            try
            {
                DB::begintransaction();
                $order = Order::find($order_id);
                if ($order != null)
                {
                    if (!$order->status == 2)
                    {
                        throw new PDOException("invaild", -2);
                    }
                    $order->status = 3;
                    if (!$order->save())
                    {
                        throw new PDOException("unknow", -3);
                    }
                    DB::commit();

                    return Response::json(array("status" => 1));
                }
                else
                {
                    throw new PDOException("notfount", -1);
                }
            }
            catch (PDOException $e)
            {
                DB::rollback();

                return Response::json(array("status" => $e->getCode(), "error" => $e->getMessage()));
            }
        }

        public function postReject()
        {
            $order_id = Input::get("order_id");
            try
            {
                DB::begintransaction();
                $order = Order::find($order_id);
                if ($order != null)
                {
                    if ($order->status == 1 || $order->status == 5)
                    {
                        throw new PDOException("invaild", -2);
                    }
                    $order->status = 4;
                    if (!$order->save())
                    {
                        throw new PDOException("unknow", -3);
                    }
                    DB::commit();

                    return Response::json(array("status" => 1));
                }
                else
                {
                    throw new PDOException("notfount", -1);
                }
            }
            catch (PDOException $e)
            {
                DB::rollback();

                return Response::json(array("status" => $e->getCode(), "error" => $e->getMessage()));
            }
        }
    }