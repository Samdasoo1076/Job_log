							<div class="board-list">
								<table class="tbl">
									<caption>
										<strong>문의사항 목록</strong>
										<p>No., 제목, 작성자, 등록일, 문의결과 순서로 구성됨</p>
									</caption>
									<colgroup>
										<col style="width:120rem" />
										<col style="width:auto" />
										<col style="width:200rem" />
										<col style="width:120rem" />
										<col style="width:120rem" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">No.</th>
											<th scope="col">제목</th>
											<th scope="col">작성자</th>
											<th scope="col">등록일</th>
											<th scope="col">문의결과</th>
										</tr>
									</thead>
									<tbody>
										<?
									$nCnt = 0;
									if (sizeof($arr_rs) > 0) {

										for ($j = 0; $j < sizeof($arr_rs); $j++) {

											$rn = trim($arr_rs[$j]["rn"]);
											$B_NO = trim($arr_rs[$j]["B_NO"]);
											$B_RE = trim($arr_rs[$j]["B_RE"]);
											$B_PO = trim($arr_rs[$j]["B_PO"]);
											$B_CODE = trim($arr_rs[$j]["B_CODE"]);
											$CATE_01 = trim($arr_rs[$j]["CATE_01"]);
											$CATE_02 = trim($arr_rs[$j]["CATE_02"]);
											$CATE_03 = trim($arr_rs[$j]["CATE_03"]);
											$CATE_04 = trim($arr_rs[$j]["CATE_04"]);
											$WRITER_ID = trim($arr_rs[$j]["WRITER_ID"]);
											$WRITER_NM = trim($arr_rs[$j]["WRITER_NM"]);
											$TITLE = SetStringFromDB($arr_rs[$j]["TITLE"]);
											$REG_ADM = trim($arr_rs[$j]["REG_ADM"]);
											$HIT_CNT = trim($arr_rs[$j]["HIT_CNT"]);
											$REF_IP = trim($arr_rs[$j]["REF_IP"]);
											$MAIN_TF = trim($arr_rs[$j]["MAIN_TF"]);
											$USE_TF = trim($arr_rs[$j]["USE_TF"]);
											$COMMENT_TF = trim($arr_rs[$j]["COMMENT_TF"]);
											$REG_DATE = trim($arr_rs[$j]["REG_DATE"]);
											$SECRET_TF = trim($arr_rs[$j]["SECRET_TF"]);
											$F_CNT = trim($arr_rs[$j]["F_CNT"]);
											$REPLY = trim($arr_rs[$j]["REPLY"]);
											$REPLY_DATE = trim($arr_rs[$j]["REPLY_DATE"]);
											$REPLY_STATE = trim($arr_rs[$j]["REPLY_STATE"]);
											$TOP_TF = trim($arr_rs[$j]["TOP_TF"]);
											$REPLY_ADM = trim($arr_rs[$j]["REPLY_ADM"]);
											$COLOR_TAG = "";
											$highlightedTitle = highlightKeyword($TITLE, $s); // 검색어 강조 적용

											switch (trim($CATE_01)) {
												case "":
													$COLOR_TAG = "<span class='badge badge__color1'>공통</span>";
													break;
												case "ALL":
													$COLOR_TAG = "<span class='badge badge__color1'>공통</span>";
													break;
												case "SUSI":
													$COLOR_TAG = "<span class='badge badge__color3'>수시</span>";
													break;
												case "JEONGSI":
													$COLOR_TAG = "<span class='badge badge__color2'>정시</span>";
													break;
												case "SUNGIN":
													$COLOR_TAG = "<span class='badge badge__color6'>성인·재직자</span>";
													break;
												case "JEOEGUK":
													$COLOR_TAG = "<span class='badge badge__color4'>재외국민</span>";
													break;
												case "PYEONIP":
													$COLOR_TAG = "<span class='badge badge__color7'>편입학</span>";
													break;
												case "SCHOOL":
													$COLOR_TAG = "<span class='badge badge__color5'>고교연계</span>";
													break;
												case "ETC":
													$COLOR_TAG = "<span class='badge badge__color6'>기타</span>";
													break;
											}

											$CATE_01 = str_replace("^", " & ", $CATE_01);

											$is_new = "";
											if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
												if ($MAIN_TF <> "N") {
													$is_new = "<img src='../images/bu/ic_new.png' alt='새글' width='35'/> ";
												}
											}

											$REG_DATE = date("Y.m.d", strtotime($REG_DATE));

											$space = "";

											$DEPTH = strlen($B_PO);

											for ($l = 0; $l < $DEPTH; $l++) {
												if ($l != 1)
													$space .= "&nbsp;";
												else
													$space .= "&nbsp;";

												if ($l == ($DEPTH - 1))
													$space .= "&nbsp;┗";

												$space .= "&nbsp;";
											}

											?>
											<tr>
												<td class="tbl-no">
													<? if ($TOP_TF == "Y") { ?>
														<p class="noti">공지</p><!-- Case 공지사항 -->
													<? } else { ?>
														<p><?= $rn ?></p>
													<? } ?>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="<? if ($_SESSION['m_id'] == $WRITER_ID) { ?>view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>
														<? } else { ?>
														javascript:void(0);<? } ?>" 
															class="link"
															<? if ($_SESSION['m_id'] != $WRITER_ID) { ?>
																onclick="alert('자신이 작성한 문의만 보실수 있습니다.'); return false;"
															<? } ?>
														>
															<?= $highlightedTitle ?>
														</a>
														<? if ($F_CNT > 0) { ?>
															<a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>" class="file">
																<span class="blind">첨부파일</span>
															</a>
														<? } ?>
													</p>
												</td>
												<td class="tbl-writer">
													<p><?= maskName($WRITER_NM) ?></p>
												</td>
												<td class="tbl-date">
													<p><?= $REG_DATE ?></p>
												</td>
												<td class="tbl-feedback">
													 <? if ($REPLY_STATE == "N") { ?>
														<p class="label-feedback wait">답변대기</p>
													<? } elseif ($REPLY_STATE == "Y") { ?>
														<p class="label-feedback comp">답변완료</p>
													<? } ?>
												</td>
											</tr>
										<?
										}
									} else {
										?>
										<tr>
											<td colspan="5" class="no-list">
												<p>검색결과가 없습니다.</p>
											</td>
										</tr>
									<?
									}
									?>
									</tbody>
								</table>
							</div>
					</div>