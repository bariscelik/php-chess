<?php
error_reporting(E_ERROR);
/**
* 
*/
class Algorithm
{
    private $_json;
    public  $tm = [[['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']],
    [['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b'],['t' => 'k', 'c' => 'b']]];

    public $tmp_tm = [];

    public $board = false;

    private $result;

    private $sm = [];

    public function __construct(int $board_id)
    {
        if(($this->board = Boards::findFirst($board_id)) == false)
            return false;

        $this->tm = json_decode($this->board->tm, true);
        $this->sm = json_decode($this->board->sm, true);
        $this->history = $this->board->history;
    }

    public function canMove($cpos, $tpos, $TM = null) : bool
    {
        if($TM==null)
            $TM = $this->tm;

        if ($tpos->row >= 0 && $tpos->row < 8 && $tpos->col >= 0 && $tpos->col < 8) {
            $cItem = $TM[$cpos->row][$cpos->col];
            $tItem = $TM[$tpos->row][$tpos->col];
            $tr_cr = $tpos->row - $cpos->row;
            $cr_tr = $cpos->row - $tpos->row;
            $absR = abs($cr_tr);
            $tc_cc = $tpos->col - $cpos->col;
            $cc_tc = $cpos->col - $tpos->col;
            $absC = abs($cc_tc);
            $signR_t_c = abs($tr_cr) / $tr_cr;
            $signC_t_c = abs($tc_cc) / $tc_cc;

            $pathFree = true;

            if ($cItem['c'] != $tItem['c']) {

                switch ($cItem['t']) {
                    case 'b':
                        if ($absR == $absC) {
                            $j = $cpos->col;
                            
                            for ($i = $cpos->row + $signR_t_c; $i != $tpos->row; $i = $i + $signR_t_c) {
                                $j = $j + $signC_t_c;
                                if ($TM[$i][$j] != 0) $pathFree = false;
                            }
                        return $pathFree;
                    }
                        break;
                    case 'k':
                        if ($absR <= 1 && $absC <= 1) return true;
                        break;
                    case 'n':
                        if ($absR === 2 && $absC === 1) return true;
                        if ($absC === 2 && $absR === 1) return true;
                        break;
                    case 'p':
                        if ($cItem['c'] === 'b' && $tr_cr > 0) {
                            if ($tItem === 0 && $cpos->col === $tpos->col) {
                                if ($cpos->row === 1 && $absR <= 2) return true;
                                if ($cpos->row > 1 && $absR <= 1) return true;
                            } else if ($tItem !== 0 && $absC === 1 && $absR === 1) {
                                return true;
                            }
                        } else if ($cItem['c'] === 'w' && $cr_tr > 0) {
                            if ($tItem === 0 && $cpos->col === $tpos->col) {
                                if ($cpos->row === 6 && $absR <= 2) return true;
                                if ($cpos->row < 6 && $absR <= 1) return true;
                            } else if ($tItem !== 0 && $absC === 1 && $absR === 1) {
                                return true;
                            }
                        }
                        break;
                    case 'q':
                        if ($absR === $absC) {
                            $j = $cpos->col;
                            for ($i = $cpos->row + $signR_t_c; $i !== $tpos->row; $i = $i + $signR_t_c) {
                                $j = $j + $signC_t_c;
                                if ($TM[$i][$j] !== 0) $pathFree = false;
                            }
                        return $pathFree;
                    }
                        if ($absR === 0) {
                            for ($i = $cpos->col + $signC_t_c; $i !== $tpos->col; $i = $i + $signC_t_c) {
                                if ($TM[$cpos->row][$i] !== 0) {
                                    $pathFree = false;
                                }
                            }
                        return $pathFree;
                    } else if ($absC === 0) {
                            for ($i = $cpos->row + $signR_t_c; $i !== $tpos->row; $i = $i + $signR_t_c) {
                                if ($TM[$i][$cpos->col] !== 0) {
                                    $pathFree = false;
                                }
                            }
                        return $pathFree;
                    }
                        break;
                    case 'r':
                        if ($absR === 0) {
                            for ($i = $cpos->col + $signC_t_c; $i !== $tpos->col; $i = $i + $signC_t_c) {
                                if ($TM[$cpos->row][$i] !== 0) {
                                    $pathFree = false;
                                }
                            }
                        return $pathFree;
                    } else if ($absC === 0) {
                            for ($i = $cpos->row + $signR_t_c; $i !== $tpos->row; $i = $i + $signR_t_c) {
                                if ($TM[i][$cpos->col] !== 0) {
                                    $pathFree = false;
                                }
                            }
                        return $pathFree;
                    }
                        break;
                }
            }
        }


        return false;
    }

    public function move($cpos, $tpos) : bool
    {
        if($this->canMove($cpos, $tpos) && $this->isCheckMate($cpos, $tpos))
        {
            if($this->tm[$tpos->row][$tpos->col] != 0)
            {
                $this->sm[] = $this->tm[$tpos->row][$tpos->col];
            }

            $this->tm[$tpos->row][$tpos->col] = $this->tm[$cpos->row][$cpos->col];
            $this->tm[$cpos->row][$cpos->col] = 0;



            $this->board->tm = json_encode($this->tm);
            $this->board->sm = json_encode($this->sm);

            if($this->board->save() == true)
            {
                $this->result = ['move' => true,
                           'TM' => $this->tm,
                           'SM' => $this->sm];
                return true;
            }

        }

        $this->result = ['move' => false,
               'TM' => $this->tm,
               'SM' => $this->sm];

        return false;
    }

    /**
     * if king is under attack
     *
     * @param $cpos
     * @param $tpos
     * @return bool
     */
    public function isCheckMate($cpos, $tpos) : bool
    {

        // item at current pos
        $cItem = $this->tm[$cpos->row][$cpos->col];

        // item at target pos
        $tItem = $this->tm[$tpos->row][$tpos->col];

        // position of kings
        $BLACK_KING_POS->row = 0;
        $BLACK_KING_POS->col = 0;

        $WHITE_KING_POS->row = 0;
        $WHITE_KING_POS->col = 0;

        // copy TM to tmp_TM
        $tmp_TM = $this->tm;

        // virtual movement
        $tmp_TM[$tpos->row][$tpos->col] = $tmp_TM[$cpos->row][$cpos->col];
        $tmp_TM[$cpos->row][$cpos->col] = 0;

        // find & set king positions
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {

                if($tmp_TM[$i][$j]['t'] == "k" && $tmp_TM[$i][$j]['c'] == "b")
                {
                    $BLACK_KING_POS->row = $i;
                    $BLACK_KING_POS->col = $j;

                }else if($tmp_TM[$i][$j]['t'] == "k" && $tmp_TM[$i][$j]['c'] == "w")
                {
                    $WHITE_KING_POS->row = $i;
                    $WHITE_KING_POS->col = $j;
                }

            }
        }


        $piecePos->row = 0;
        $piecePos->col = 0;

        if($cItem['c'] == 'b')
        {
            for($i = 0; $i < 8; $i++)
            {
                for($j = 0; $j < 8; $j++)
                {
                    if($tmp_TM[$i][$j]['c'] == 'w')
                    {
                        $piecePos->row = $i;
                        $piecePos->col = $j;

                        if($this->canMove($piecePos, $BLACK_KING_POS, $tmp_TM) == true)
                            return false;
                    }
                }
            }
        }else if($cItem['c'] == 'w')
        {
            for($i = 0; $i < 8; $i++)
            {
                for($j = 0; $j < 8; $j++)
                {
                    if($tmp_TM[$i][$j]['c'] == 'b')
                    {
                        $piecePos->row = $i;
                        $piecePos->col = $j;

                        if($this->canMove($piecePos, $WHITE_KING_POS, $tmp_TM) == true)
                            return false;
                    }
                }
            }
        }

        return true;
    }

    public function output() : string
    {

        return json_encode($this->result);
    }

}