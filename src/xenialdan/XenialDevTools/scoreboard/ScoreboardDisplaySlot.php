<?php

namespace xenialdan\XenialDevTools\scoreboard;

interface ScoreboardDisplaySlot
{
    public const LIST = "list";//Pause UI - In the pause menu
    public const SIDEBAR = "sidebar";//Screen UI - Scoreboard on right side of display
    public const BELOWNAME = "belowname";//currently not enabled in the MCPE command, but might work in servers? TODO

    /*
     * sub_2B00514(&v20, &Scoreboard::DISPLAY_SLOT_LIST);
     * sub_2B00514(&v21, &Scoreboard::DISPLAY_SLOT_SIDEBAR);
     * sub_2B00514(&v22, &Scoreboard::DISPLAY_SLOT_BELOWNAME);
     */
}