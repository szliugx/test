2016年5月15日
====================

误区纠正
-------------------

###可购买日（下单的当天）
		判断商品某一日是否可以购买
		首先判断当天是否在销售日期内，（不在销售日期内，所有日期变成不可售）
		判断选择日的那天在价格日历中的体现，0为不可使用，abcd对应4种不同的价格
		商品预售时间，当前时间加上最大预售期时间，是否在可购买开始时间,在可售卖，否则不可卖
		当前日期大于可购买结束时间不可以购买
		选择日的商品不在合同期内，不可售卖