<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//前台
Route::group(['namespace'=>'Frontend', 'middleware' => 'RTFrontend'], function () {
	// 首頁
	Route::get('/', ['uses'=>'Index@pageIndex']);
	// 設備
	Route::get('device', ['uses'=>'Device@pageDevice'])->name('device');
	// 設備(Top50)
	Route::get('device/top50/{game_id}', ['uses'=>'Device@pageDeviceTop50']);
	// 標題
	Route::get('titles', ['uses'=>'Titles@pageTitles'])->name('titles');
	// 行程
	Route::get('schedule', ['uses'=>'Schedule@pageSchedule']);
	// 行程-日曆
	Route::get('schedule/calendar', ['uses'=>'Schedule@pageScheduleCalendar']);
	// 高分賽-結果
	Route::get('highscore/result/{event_id}', ['uses'=>'Highscore@pageHighScoreResult'])->where('event_id', '[0-9]+');
	// 高分賽-Top50
	Route::get('highscore/top50/{event_id}', ['uses'=>'Highscore@pageHighScoreTop50'])->where('event_id', '[0-9]+');
	// 淘汰賽-結果
	Route::get('tournament/result/{event_id}', ['uses'=>'Tournament@pageTournamentResult'])->where('event_id', '[0-9]+');
	// 淘汰賽-對戰組合
	Route::get('tournament/brackrt/{event_id}', ['uses'=>'Tournament@pageTournamentBrackrt'])->where('event_id', '[0-9]+');
});

//Api 核心
	// 登入
	Route::post('login', ['uses'=>'CurlAPI@userLogin'])->name('login');
	// 登出
	Route::get('logout', ['uses'=>'CurlAPI@userLogout']);
	// 取得好友列表
	Route::get('friends', ['uses'=>'CurlAPI@getFriends']);
	// 取得好友邀請
	Route::get('invitation', ['uses'=>'CurlAPI@getInvitation']);
	// 取得黑名單
	Route::get('blacklist', ['uses'=>'CurlAPI@getBlacklist']);
	// 搜尋好友
	Route::get('find/potential/friends/{id}', ['uses'=>'CurlAPI@findPotentialFriends']);
	// 新增好友
	Route::get('add/friends', ['uses'=>'CurlAPI@addFriends']);
	// 更新好友狀態
	Route::get('update/friendship', ['uses'=>'CurlAPI@updateFriendship']);
	// 取得頭像
	Route::get('avatars', ['uses'=>'CurlAPI@getAvatars']);
	// 上傳頭像
	Route::get('upload/avatars', ['uses'=>'CurlAPI@uploadAvatars']);
	// 更新帳號
	Route::get('update/account', ['uses'=>'CurlAPI@updateAccount']);
	// 比賽日曆
	Route::get('tournament/calendar', ['uses'=>'CurlAPI@getTournamentCalendar']);
	// 比賽列表
	Route::get('tournament/list', ['uses'=>'CurlAPI@getTournamentList']);
	// 比賽資料
	Route::get('tournament/{tournament_id}', ['uses'=>'CurlAPI@getTournament'])->where('tournament_id', '[0-9]+');
	// 模型列表
	Route::get('model/list', ['uses'=>'CurlAPI@getModelList']);
	// 排行榜 (Top5)
	Route::get('leaderboard/list', ['uses'=>'CurlAPI@getLeaderboardList']);
	// After
	Route::get('leaderboard/after', ['uses'=>'CurlAPI@getLeaderboardAfter']);
	// 排行榜 (Top50)
	Route::get('leaderboard/{game_id}', ['uses'=>'CurlAPI@getLeaderboard'])->where('game_id', '[0-9]+');
	// 帳號最高分
	Route::get('user/heightscore', ['uses'=>'CurlAPI@getUserHeightScore']);
	// Titles After
	Route::get('titles/after', ['uses'=>'CurlAPI@getTitleLeaderboardAfter']);
	// After
	Route::get('event/all', ['uses'=>'CurlUSAAPI@viewEventAllData']);
	// 建立團隊
	Route::post('create/team', ['uses'=>'CurlUSAAPI@createTeam'])->name('createTeam');







