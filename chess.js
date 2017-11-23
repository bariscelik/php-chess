// ********************** //
// **** VAR DEFAULTS **** //
// ********************** //

// chess piece types
var pieces = {
    WBISHOP : {t: 'b', c: 'w'},
    WKING   : {t: 'k', c: 'w'},
    WKNIGHT : {t: 'n', c: 'w'},
    WPAWN   : {t: 'p', c: 'w'},
    WQUEEN  : {t: 'q', c: 'w'},
    WROCK   : {t: 'r', c: 'w'},
    BBISHOP : {t: 'b', c: 'b'},
    BKING   : {t: 'k', c: 'b'},
    BKNIGHT : {t: 'n', c: 'b'},
    BPAWN   : {t: 'p', c: 'b'},
    BQUEEN  : {t: 'q', c: 'b'},
    BROCK   : {t: 'r', c: 'b'}
};

// table matrix
var TM = [
    [pieces.BROCK, pieces.BKNIGHT, pieces.BBISHOP, pieces.BQUEEN, pieces.BKING, pieces.BBISHOP, pieces.BKNIGHT, pieces.BROCK],
    [pieces.BPAWN, pieces.BPAWN, pieces.BPAWN, pieces.BPAWN, pieces.BPAWN, pieces.BPAWN, pieces.BPAWN, pieces.BPAWN],
    [0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0],
    [pieces.WPAWN, pieces.WPAWN, pieces.WPAWN, pieces.WPAWN, pieces.WPAWN, pieces.WPAWN, pieces.WPAWN, pieces.WPAWN],
    [pieces.WROCK, pieces.WKNIGHT, pieces.WBISHOP, pieces.WQUEEN, pieces.WKING, pieces.WBISHOP, pieces.WKNIGHT, pieces.WROCK],
];

// space matrix
var SM = [];

// temporary table matrix
var tmp_TM = JSON.parse(JSON.stringify(TM));

// table memory that store all movements
var tableHistory = [];

var isItem = false;

var cpos  = {row : 0, col : 0};  // current position
var tpos  = {row : 0, col : 0};  // target position

// selected item's dom object
var item;

/**
 * Update and draw table
 * @return void
 */
function updateTable()
{

    // clear container
    $("#chessBoard").html("");
    $("#bottomBar").html("");

    // append items to it's position
    for (var i = 0; i < 8; i++) {
        
        for (var j = 0; j < 8; j++) {
            if(TM[i][j]!=0)
            {
                var top  = i * 12.5;
                var left = j * 12.5;

                var piece = "<i class=\"piece icon-" + TM[i][j].t + " "+ TM[i][j].c +"\" style=\"top: " + top + "%; left: " + left + "%; \"></i>";

                $("#chessBoard").append(piece);
                
            }
        }

    }

    // append items to it's position
    for (var i = 0; i < SM.length; i++) {
            if(SM[i]!=0)
            {

                var piece = "<i class=\"piece icon-" + SM[i].t + " "+ SM[i].c +"\"></i>";

                $("#bottomBar").append(piece);
                
            }
    }

}

/**
 * Convert json data to item's html
 * @param {[type]} jsondata [description]
 */
function JSON2Items(jsondata)
{

    updateTable();
}


function oldMOVEE()
{
    if (tpos.row < 8 && tpos.col < 8) {
        var cItem = cTM[cpos.row][cpos.col];
        var tItem = cTM[tpos.row][tpos.col];
        const tr_cr = tpos.row - cpos.row;
        const cr_tr = cpos.row - tpos.row;
        const absR = Math.abs(cr_tr);
        const tc_cc = tpos.col - cpos.col;
        const cc_tc = cpos.col - tpos.col;
        const absC = Math.abs(cc_tc);
        const signR_t_c = Math.abs(tr_cr) / tr_cr;
        const signC_t_c = Math.abs(tc_cc) / tc_cc;

        var pathFree = true;


        if (cItem.color != tItem.color) {
            switch (cItem.t) {
                case 'b':
                    if (absR === absC) {
                        var j = cpos.col;
                        for (var i = cpos.row + signR_t_c; i != tpos.row; i = i + signR_t_c) {
                            j = j + signC_t_c;
                            if (cTM[i][j] != 0) pathFree = false;
                        }
                        return pathFree;
                    }
                    break;
                case 'k':
                    if (absR <= 1 && absC <= 1) return true;
                    break;
                case 'n':
                    if (absR === 2 && absC === 1) return true;
                    if (absC === 2 && absR === 1) return true;
                    break;
                case 'p':
                    if (cItem.color === 'b' && tr_cr > 0) {
                        if (tItem === 0 && cpos.col === tpos.col) {
                            if (cpos.row === 1 && absR <= 2) return true;
                            if (cpos.row > 1 && absR <= 1) return true;
                        } else if (tItem !== 0 && absC === 1 && absR === 1) {
                            return true;
                        }
                    } else if (cItem.color === 'w' && cr_tr > 0) {
                        if (tItem === 0 && cpos.col === tpos.col) {
                            if (cpos.row === 6 && absR <= 2) return true;
                            if (cpos.row < 6 && absR <= 1) return true;
                        } else if (tItem !== 0 && absC === 1 && absR === 1) {
                            return true;
                        }
                    }
                    break;
                case 'q':
                    if (absR === absC) {
                        var j = cpos.col;
                        for (var i = cpos.row + signR_t_c; i !== tpos.row; i = i + signR_t_c) {
                            j = j + signC_t_c;
                            if (cTM[i][j] !== 0) pathFree = false;
                        }
                        return pathFree;
                    }
                    if (absR === 0) {
                        for (var i = cpos.col + signC_t_c; i !== tpos.col; i = i + signC_t_c) {
                            if (cTM[cpos.row][i] !== 0) {
                                pathFree = false;
                            }
                        }
                        return pathFree;
                    } else if (absC === 0) {
                        for (var i = cpos.row + signR_t_c; i !== tpos.row; i = i + signR_t_c) {
                            if (cTM[i][cpos.col] !== 0) {
                                pathFree = false;
                            }
                        }
                        return pathFree;
                    }
                    break;
                case 'r':
                    if (absR === 0) {
                        for (var i = cpos.col + signC_t_c; i !== tpos.col; i = i + signC_t_c) {
                            if (cTM[cpos.row][i] !== 0) {
                                pathFree = false;
                            }
                        }
                        return pathFree;
                    } else if (absC === 0) {
                        for (var i = cpos.row + signR_t_c; i !== tpos.row; i = i + signR_t_c) {
                            if (cTM[i][cpos.col] !== 0) {
                                pathFree = false;
                            }
                        }
                        return pathFree;
                    }
                    break;
            }
        }
    }
    return false;

}



function move(cpos, tpos)
{

    $.post( "chess.php?_url=/fetch/1", { 'cpos': JSON.stringify(cpos) , 'tpos': JSON.stringify(tpos) })

        .done(function( data ) {

            var d = JSON.parse(data);

            if(d['move'] == true)
            {
                if(d['TM'] != null)
                    TM = d['TM'];
                if(d['SM'] != null)
                    SM = d['SM'];

                
                updateTable();
            }

        })

    .fail(function(){

      window.alert('hata oluştu');

    });
}

function fetch()
{
    $.post( "chess.php?_url=/fetch/1", { 'cpos': JSON.stringify(cpos) , 'tpos': JSON.stringify(tpos) })

        .done(function( data ) {

            var d = JSON.parse(data);

            if(d['TM'] != null)
                TM = d['TM'];
            if(d['SM'] != null)
                SM = d['SM'];


            updateTable();

        })

        .fail(function(){

            window.alert('hata oluştu');

        });
}

/**
 * Check is if under attack
 * @param  {[type]}  cpos current position
 * @param  {[type]}  tpos target position
 * @return {Boolean}      [description]
 */
function isCheckMate(cpos, tpos)
{

    // item at current pos
    var cItem = TM[cpos.row][cpos.col];

    // item at target pos
    var tItem = TM[tpos.row][tpos.col];

    // position of kings
    var BLACK_KING_POS = {row:0, col:0};
    var WHITE_KING_POS = {row:0, col:0};

    // copy TM to tmp_TM
    tmp_TM = JSON.parse(JSON.stringify(TM));

    // virtual movement
    tmp_TM[tpos.row][tpos.col] = tmp_TM[cpos.row][cpos.col];
    tmp_TM[cpos.row][cpos.col] = 0;

    // find & set king positions
    for (var i = 0; i < 8; i++) {
        for (var j = 0; j < 8; j++) {

            if(tmp_TM[i][j].t == "k" && tmp_TM[i][j].color == "b")
            {
                BLACK_KING_POS = {row:i, col:j};
            }else if(tmp_TM[i][j].t == "k" && tmp_TM[i][j].color == "w")
            {
                WHITE_KING_POS = {row:i, col:j};
            }

        }
    }

    var piecePos = {row : 0, col : 0};

    if(cItem.color == 'b')
    {
        for(var i = 0; i < 8; i++)
        {
            for(var j = 0; j < 8; j++)
            {
                if(tmp_TM[i][j].color == 'w')
                {
                    piecePos = {row: i, col: j};

                    if(canMove(piecePos, BLACK_KING_POS, tmp_TM) == true)
                        return false;
                }
            }
        }
    }else if(cItem.color == 'w')
    {
        for(var i = 0; i < 8; i++)
        {
            for(var j = 0; j < 8; j++)
            {
                if(tmp_TM[i][j].color == 'b')
                {
                    piecePos = {row: i, col: j};

                    if(canMove(piecePos, WHITE_KING_POS, tmp_TM) == true)
                        {return false;}
                }
            }
        }
    }

    return true;
    
}

function paintAvailableTargets(cpos)
{

}

$(document).on("click", ".piece", function (e) { //Relative ( to its parent) mouse position 

    item = $(this);
    isItem = true;

});

/**
 * [setTaken description]
 * @param {[type]} itt itemtotaken
 */
function setTaken(itt, cpos, tpos)
{
    if(TM[tpos.row][tpos.col] != 0)
    {
        SM.push(TM[tpos.row][tpos.col]);
    }
}

$(document).on("click", "#chessBoard", function (e) { //Relative ( to its parent) mouse position 

    // table size
    var tableWidth = $("#chessBoard").width() / 8;
    var tableHeight = $("#chessBoard").height() / 8;

    // mouse click position (in px)
    var posX = e.pageX - $(this).offset().left;
    var posY = e.pageY - $(this).offset().top;

    // calculate clicked row & col
    tpos.row = Math.floor((posY)/tableHeight),
    tpos.col = Math.floor((posX)/tableHeight);

    // an item selection
    if($(".piece.selected").length == 0 && isItem)
    {

        item.addClass("selected");
        
        cpos.row = tpos.row;
        cpos.col = tpos.col;
        
        //paintAvailableTargets(cpos);
        
    } // clicked to target position
    else if($(".piece.selected").length == 1)
    {

        move(cpos, tpos);

        /*if(canMove(cpos, tpos))
        {
            //setTaken(item, cpos, tpos);

            //TM[tpos.row][tpos.col] = TM[cpos.row][cpos.col];
            //TM[cpos.row][cpos.col] = 0;

            updateTable();

            //tableHistory.push({cpos, tpos});
        }*/

        $(".piece").removeClass('selected');

        
        
    }

    isItem = false;
    
});



$("#updateBtn").click(function (e) { //Relative ( to its parent) mouse position 
    updateTable();
});

$("#undoBtn").click(function (e) { //Relative ( to its parent) mouse position 
    /*tableHistory[tableHistory.length-1]
    tableHistory.pop();*/
});

// initialize piece positions
updateTable();

//console.log(JSON.stringify(TM));

fetch();