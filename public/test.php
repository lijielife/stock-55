<?php


        foreach ($firstSides as $firstSide) {
            $firstSide = new DataLogBean($firstSide);
            $firstPrice = (float) $firstSide->getPrice();
            $firstVolume = (int) $firstSide->getVolume();
            $firstId = (int) $firstSide->getId();

            if (in_array($firstId, $idIsUse)) {
                continue;
            }

            $secondSideTemp = NULL;
            $plans = array();
            $diffTemp = 0;
            foreach ($secondSides as $id => $secondSide) {
                if (in_array($secondSide->getId(), $idIsUse)) {
                    continue;
                }
                $secondVolume = (int) $secondSide->getVolume();
                $secondPrice = (float) $secondSide->getPrice();
                $diff = ((float) $secondPrice - $firstPrice) * $conf;
                if ($firstVolume == $secondVolume && ($diff > $diffTemp)) {
                    $secondSideTemp = $secondSide;
                    $diffTemp = $diff;
//                            break;
                } else if ($secondVolume < $firstVolume) {
                    foreach ($plans as $key => $plan) {
                        $totalVolume = $plan->getTotalVolume();
                        $secondTotalVolume = $secondSide->getVolume();

                        if ($totalVolume + $secondTotalVolume <= $firstVolume) {
                            $plan->addDataLogBeanArr($secondSide);
                        }
                    }
                    $this->addPlan($plans, buySide);
                }
            }

            if ($secondSideTemp !== NULL) {
                $this->saveDataToDB($idIsUse, $firstSide, array($secondSideTemp));
            } else {
                if (!empty($plans)) {
                    usort($plans, array($this, "plansCmp"));

                    $plan = $this->selectPlan($plans, $firstSide);
                    if ($plan !== null) {
                        $this->saveDataToDB($idIsUse, $firstSide, $plan->getDataLogBeanArr());
                    }
                }
            }
        }