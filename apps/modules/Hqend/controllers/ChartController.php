<?php

namespace Hqend\Controllers;

class ChartController extends ControllerBase {

    public function listLinkAction()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $data_search = $this->request->getPost("data_search", 'trim', false);
        if (!isset($data_search["to_date"]) || empty($data_search["to_date"]))
        {
            $data_search["to_date"] = date("Y-m-d");
        }
        if (!isset($data_search["from_date"]) || empty($data_search["from_date"]))
        {
            $data_search["from_date"] = date("Y-m-d");
        }
        $date_ranges = \HqEngine\HqTool\HqUtil::getRangeDate($data_search["from_date"], $data_search["to_date"], "Y-m-d");
        if (count($date_ranges) > 31)
        {
            echo "alert('" . $this->language->__("Date Range Must < 31 days") . "');";
            exit();
        }
        $links = array();
        if (isset($data_search["link_id"]) && !empty($data_search["link_id"]) && is_numeric($data_search["link_id"]))
        {
            $link_detail = \Hqend\Models\Links::getLinkById($data_search["link_id"]);
            if (!empty($link_detail))
            {
                $link_detail = $link_detail->toArray();
                $links[] = $link_detail;
            }
        }
        if (empty($links))
        {
            $links = \Hqend\Models\Links::getFilter($data_search);
        }

        $list_id = "";
        foreach ($links as $link)
        {
            $list_id .=$link["link_id"] . ",";
        }
        $list_id                       = substr($list_id, 0, strlen($list_id) - 1);
        $link_dates                    = \Hqend\Models\LinksDate::getFilter($data_search, $list_id);
        $this->view->data_click        = \HqEngine\HqTool\HqUtil::mergeChartDateRange($link_dates, $data_search["from_date"], $data_search["to_date"], "link_click");
        $this->view->data_unique_click = \HqEngine\HqTool\HqUtil::mergeChartDateRange($link_dates, $data_search["from_date"], $data_search["to_date"], "link_unique_click");
        $this->view->data_view         = \HqEngine\HqTool\HqUtil::mergeChartDateRange($link_dates, $data_search["from_date"], $data_search["to_date"], "link_view");
        $this->view->data_revenue      = \HqEngine\HqTool\HqUtil::mergeChartDateRange($link_dates, $data_search["from_date"], $data_search["to_date"], "link_revenue");
        $this->view->data_register     = \HqEngine\HqTool\HqUtil::mergeChartDateRange($link_dates, $data_search["from_date"], $data_search["to_date"], "link_register");
    }

}
